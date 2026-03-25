<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\felhasznalo;
use App\Models\Tranzakcio;
use App\Models\kategoria;
use App\Models\penznem;
use App\Models\arbazis;
use App\Http\Controllers\StatController;
use App\Services\ExchangeRateService;

function defaultCategoryNames(): array
{
    return [
        'bevásárlás',
        'szórakozás',
        'vendéglátás',
        'egészség',
        'ruházat',
        'közlekedés',
        'befektetés',
        'készpénzbefizetés',
    ];
}

function getSuggestedCategories(int $userId)
{
    $databaseCategories = kategoria::where('felhasznaloid', $userId)
        ->orWhereNull('felhasznaloid')
        ->get();

    $defaultCategories = collect(defaultCategoryNames())->map(function ($nev) {
        return new kategoria([
            'felhasznaloid' => null,
            'nev' => $nev,
        ]);
    });

    return $defaultCategories
        ->concat($databaseCategories)
        ->groupBy(function ($kategoria) {
            return mb_strtolower(trim($kategoria->nev));
        })
        ->map(function ($group) {
            return $group->sortByDesc(function ($kategoria) {
                return $kategoria->felhasznaloid ? 1 : 0;
            })->first();
        })
        ->sortBy('nev', SORT_NATURAL | SORT_FLAG_CASE)
        ->values();
}

function findOrCreateCategory(string $kategoriaNev, int $userId): kategoria
{
    $trimmedName = trim($kategoriaNev);

    $existingCategory = kategoria::whereRaw('LOWER(TRIM(nev)) = LOWER(?)', [$trimmedName])
        ->where(function ($q) use ($userId) {
            $q->where('felhasznaloid', $userId)->orWhereNull('felhasznaloid');
        })
        ->orderByRaw('CASE WHEN felhasznaloid IS NULL THEN 1 ELSE 0 END')
        ->first();

    if ($existingCategory) {
        return $existingCategory;
    }

    return kategoria::create([
        'felhasznaloid' => $userId,
        'nev' => $trimmedName,
    ]);
}

function normalizeAmountValue($amount): ?float
{
    if ($amount === null) {
        return null;
    }

    $normalized = str_replace([' ', ','], ['', '.'], trim((string) $amount));

    if ($normalized === '' || !is_numeric($normalized)) {
        return null;
    }

    return (float) $normalized;
}

function normalizeTransactionType(?string $type): string
{
    return mb_strtolower(trim((string) $type));
}

function resolveTransactionType(?string $type): ?string
{
    $normalized = normalizeTransactionType($type);
    return in_array($normalized, ['koltseg', 'bevetel'], true) ? $normalized : null;
}

function ensureTipusColumnExists(): bool
{
    try {
        if (!Schema::hasColumn('tranzakcio', 'tipus')) {
            Schema::table('tranzakcio', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->string('tipus', 20)->default('koltseg');
            });
        }
        return Schema::hasColumn('tranzakcio', 'tipus');
    } catch (\Throwable $e) {
        return false;
    }
}

function normalizeCurrencyCode(?string $currency): string
{
    $normalized = strtoupper(trim((string) $currency));
    return preg_replace('/\s+/', '', $normalized);
}

function findOrCreateCurrency(string $currencyInput): ?penznem
{
    $currencyCode = normalizeCurrencyCode($currencyInput);

    if ($currencyCode === '') {
        return null;
    }

    $existingCurrency = penznem::whereRaw('UPPER(TRIM(nev)) = ?', [$currencyCode])->first();
    if ($existingCurrency) {
        return $existingCurrency;
    }

    if (!preg_match('/^[A-Z]{3}$/', $currencyCode)) {
        return null;
    }

    return penznem::create([
        'nev' => $currencyCode,
    ]);
}

Route::get('/', function () {
    return view('login');
});
//koltsegfigyelo.test/login
Route::get('/login', function () {
    return view('login');
});

Route::post('/login', function () {
    $user = felhasznalo::where('email', request('email'))->first();
    
    if ($user && Hash::check(request('password'), $user->password)) {
        session(['user' => $user]);
        return redirect('/fooldal');
    }
    
    return back()->withErrors(['Helytelen email vagy jelszó!']);
});

Route::get('/logout', function () {
    session()->flush();
    return redirect('/login');
});

//koltsegfigyelo.test/fooldal
Route::get('/fooldal', function () {
    if (!session('user')) {
        return redirect('/login');
    }
    $hasTipusColumn = ensureTipusColumnExists();
    $userId = session('user')->id;

    $exchangeRateService = new ExchangeRateService();

    $tranzakciok = Tranzakcio::with('kategoria','penznem')->where('felhasznaloid', $userId)->get();
    
    // Az árfolyamok lekérése az összes tranzakcióhoz - try-catch-al kezelve, ha a tábla még nem létezik
    $arfolyamok = collect();
    try {
        $arfolyamok = arbazis::with('penznem')->get()->keyBy('penznemid');
    } catch (\Exception $e) {
        // Ha az arbazis tábla még nem létezik, üres colllection-vel dolgozunk
        $arfolyamok = collect();
    }
    
    // Az összegek átváltása forintra az ExchangeRateService segítségével
    $tranzakciokAtvalasztva = $exchangeRateService->convertAllToHUF($tranzakciok);
    
    $kategoriak = getSuggestedCategories($userId);
    $penznemek = penznem::orderBy('nev')->get();

    // Statisztikai adatok - forint-alapú számítások
    $expenseTotal = $tranzakciokAtvalasztva
        ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
        ->sum('osszeghuf');
    $incomeTotal = $tranzakciokAtvalasztva
        ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
        ->sum('osszeghuf');
    $balanceTotal = $incomeTotal - $expenseTotal;
    
    // Kategóriánkénti összegzés az ExchangeRateService segítségével
    $expenseTransactions = $tranzakciok
        ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
        ->values();
    $byCategory = $exchangeRateService->getCategoryTotalsInHUF($expenseTransactions)->take(5);

    return view('fooldal', compact('tranzakciokAtvalasztva','kategoriak','penznemek','expenseTotal','incomeTotal','balanceTotal','byCategory', 'arfolyamok', 'tranzakciok', 'hasTipusColumn'));
});

Route::get('/teszt', function () {
    $felhasznalo = felhasznalo::all();
    return view('teszt', ['felhasznalo' => $felhasznalo]);
});

Route::get('/felhasznalo/add', function () {
    return view('felhasznalo_hozzaadas');
});

Route::post('/felhasznalo/add', function () {
    felhasznalo::create([
        'nev' => request('nev'),
        'email' => request('email'),
        'password' => Hash::make(request('password')),
        'telefon' => request('telefonszam'),
        'orszag' => request('orszag'),
        'telepules' => request('telepules'),
    ]);
    return redirect('/login')->with('success', 'Regisztráció sikeres! Jelentkezz be!');
});

Route::post('/koltseg/add', function () {
    if (!session('user')) {
        return redirect('/login');
    }
    $hasTipusColumn = ensureTipusColumnExists();
    
    $kategoriaNev = trim(request('kategoria'));
    $penznemNev = request('penznem');
    $tipus = resolveTransactionType(request('tipus'));
    $userId = session('user')->id;
    $osszeg = normalizeAmountValue(request('osszeg'));

    if ($kategoriaNev === '') {
        return back()->withErrors(['kategoria' => 'Adj meg egy kategóriát.'])->withInput();
    }

    if ($osszeg === null || $osszeg < 0) {
        return back()->withErrors(['osszeg' => 'Adj meg egy érvényes összeget.'])->withInput();
    }

    if (!request('rogzites')) {
        return back()->withErrors(['rogzites' => 'Adj meg egy dátumot.'])->withInput();
    }

    if (!$tipus) {
        return back()->withErrors(['tipus' => 'Válassz tranzakció típust (költség vagy bevétel).'])->withInput();
    }

    $kat = findOrCreateCategory($kategoriaNev, $userId);

    $penznemRecord = findOrCreateCurrency($penznemNev);
    if (!$penznemRecord) {
        return back()->withErrors(['penznem' => 'A pénznem 3 betűs kód legyen (például HUF, EUR, USD).'])->withInput();
    }

    Tranzakcio::create([
        'felhasznaloid' => $userId,
        'kategoriaid' => $kat->id,
        ...($hasTipusColumn ? ['tipus' => $tipus] : []),
        'penznemid' => $penznemRecord->id,
        'osszeg' => $osszeg,
        'megjegyzes' => request('megjegyzes'),
        'rogzites' => request('rogzites')
    ]);
    
    return redirect('/fooldal')->with('success', 'Tranzakció sikeresen hozzáadva!');
});


Route::post('/kategoria/add', function () {
    if (!session('user')) {
        return redirect('/login');
    }
    
    $kategoria = kategoria::create([
        'felhasznaloid' => session('user')->id,
        'nev' => request('kategoria_nev')
    ]);
    
    return response()->json(['success' => true, 'kategoriaid' => $kategoria->id, 'kategoria_nev' => $kategoria->nev]);
    return redirect('/fooldal')->with('success', 'Költség sikeresen hozzáadva!');
});

Route::put('/koltseg/edit/{id}', function ($id) {
    if (!session('user')) {
        return redirect('/login');
    }
    $hasTipusColumn = ensureTipusColumnExists();

    $tranzakcio = Tranzakcio::find($id);

    if (!$tranzakcio) {
        return redirect('/fooldal')->withErrors(['error' => 'A költség nem található!']);
    }

    // Ellenőrzés: csak a saját költségét lehet szerkeszteni
    if ($tranzakcio->felhasznaloid !== session('user')->id) {
        return redirect('/fooldal')->withErrors(['error' => 'Nincs jogod ezt a költséget szerkeszteni!']);
    }

    $kategoriaNev = trim(request('kategoria'));
    $penznemNev = request('penznem');
    $tipus = resolveTransactionType(request('tipus'));
    $userId = session('user')->id;
    $osszeg = normalizeAmountValue(request('osszeg'));

    if ($kategoriaNev === '') {
        return back()->withErrors(['kategoria' => 'Adj meg egy kategóriát.'])->withInput();
    }

    if ($osszeg === null || $osszeg < 0) {
        return back()->withErrors(['osszeg' => 'Adj meg egy érvényes összeget.'])->withInput();
    }

    if (!request('rogzites')) {
        return back()->withErrors(['rogzites' => 'Adj meg egy dátumot.'])->withInput();
    }

    if (!$tipus) {
        return back()->withErrors(['tipus' => 'Válassz tranzakció típust (költség vagy bevétel).'])->withInput();
    }

    $kat = findOrCreateCategory($kategoriaNev, $userId);

    $penznemRecord = findOrCreateCurrency($penznemNev);
    if (!$penznemRecord) {
        return back()->withErrors(['penznem' => 'A pénznem 3 betűs kód legyen (például HUF, EUR, USD).'])->withInput();
    }

    $tranzakcio->update([
        'kategoriaid' => $kat->id,
        ...($hasTipusColumn ? ['tipus' => $tipus] : []),
        'penznemid' => $penznemRecord->id,
        'osszeg' => $osszeg,
        'megjegyzes' => request('megjegyzes'),
        'rogzites' => request('rogzites')
    ]);

    return redirect('/fooldal')->with('success', 'Tranzakció sikeresen szerkesztve!');
});

Route::delete('/koltseg/delete/{id}', function ($id) {
    if (!session('user')) {
        return redirect('/login');
    }

    $tranzakcio = Tranzakcio::find($id);

    if (!$tranzakcio) {
        return redirect('/fooldal')->withErrors(['error' => 'A költség nem található!']);
    }

    // Ellenőrzés: csak a saját költségét lehet törölni
    if ($tranzakcio->felhasznaloid !== session('user')->id) {
        return redirect('/fooldal')->withErrors(['error' => 'Nincs jogod ezt a költséget törölni!']);
    }

    $tranzakcio->delete();

    return redirect('/fooldal')->with('success', 'Tranzakció sikeresen törölve!');
});

Route::get('/arfolyam/frissites', function () {
    if (!session('user')) {
        return redirect('/login');
    }

    // Árfolyamok frissítése az API-ból
    return response()->json([
        'message' => 'Az árfolyamok frissítési parancs elindítva.',
        'info' => 'Kérjük, futtassa le az alábbi parancsot a terminálban: php artisan app:update-exchange-rates'
    ]);
});

Route::get('/statisztika', [StatController::class, 'index'])->name('statisztika.index');

// Setup route - adatbázis inicializáláshoz
Route::get('/setup/migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return response()->json(['success' => true, 'message' => 'Migrációk sikeresen futtatva!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

Route::get('/setup/seed', function () {
    try {
        // Előbb pénznemek, utána árfolyamok
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'PenznemSeeder', '--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'ArfbazisSeeder', '--force' => true]);
        return response()->json(['success' => true, 'message' => 'Pénznem és árfolyam seederek sikeresen futtatva!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

Route::get('/setup/refresh-rates', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('app:update-exchange-rates');
        return response()->json(['success' => true, 'message' => 'Árfolyamok sikeresen frissítve!']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

Route::get('/setup/all', function () {
    try {
        $result = [];
        
        $result['migrate'] = 'Migrációk futtatása...';
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $result['migrate'] = 'Sikeresen futtatva!';
        
        $result['seed'] = 'Seeder-ek futtatása...';
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $result['seed'] = 'Sikeresen futtatva!';
        
        $result['rates'] = 'Árfolyamok frissítése...';
        \Illuminate\Support\Facades\Artisan::call('app:update-exchange-rates');
        $result['rates'] = 'Sikeresen futtatva!';
        
        return response()->json(['success' => true, 'steps' => $result]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});
