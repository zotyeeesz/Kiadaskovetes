<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\felhasznalo;
use App\Models\Tranzakcio;
use App\Models\kategoria;
use App\Models\penznem;
use App\Models\arbazis;
use App\Http\Controllers\StatController;
use App\Services\ExchangeRateService;

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
    
    $rawKats = kategoria::where('felhasznaloid', $userId)->orWhereNull('felhasznaloid')->orderBy('nev', 'asc')->get();
    $kategoriak = $rawKats->groupBy(function($k){
        return mb_strtolower(trim($k->nev));
    })->map(function($group){
        return $group->sortByDesc(function($k){ return $k->felhasznaloid ? 1 : 0; })->first();
    })->values();
    $penznemek = penznem::all();

    // Statisztikai adatok - forint-alapú számítások
    $total = $tranzakciokAtvalasztva->sum('osszeghuf');
    
    // Kategóriánkénti összegzés az ExchangeRateService segítségével
    $byCategory = $exchangeRateService->getCategoryTotalsInHUF($tranzakciok)->take(5);

    return view('fooldal', compact('tranzakciokAtvalasztva','kategoriak','penznemek','total','byCategory', 'arfolyamok', 'tranzakciok'));
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
    
    $kategoriaNev = trim(request('kategoria'));
    $penznemNev = trim(request('penznem'));
    $userId = session('user')->id;

    // Kategória keresése (felhasználó-specifikus vagy globális)
    $kat = kategoria::where('nev', $kategoriaNev)
        ->where(function($q) use ($userId) {
            $q->where('felhasznaloid', $userId)->orWhereNull('felhasznaloid');
        })->first();

    // Ha nem találjuk, létrehozunk egy felhasználóspecifikus kategóriát
    if (!$kat) {
        $kat = kategoria::create([
            'felhasznaloid' => $userId,
            'nev' => $kategoriaNev
        ]);
    }

    // Pénznem keresése (case-insensitive keresés)
    $penznemRecord = penznem::whereRaw('LOWER(nev) = LOWER(?)', [$penznemNev])->first();
    if (!$penznemRecord) {
        // Ha nem létezik a pénznem, kezeljük az errort
        return back()->withErrors(['penznem' => 'A megadott pénznem nem létezik az adatbázisban.']);
    }

    Tranzakcio::create([
        'felhasznaloid' => $userId,
        'kategoriaid' => $kat->id,
        'penznemid' => $penznemRecord->id,
        'osszeg' => request('osszeg'),
        'megjegyzes' => request('megjegyzes'),
        'rogzites' => request('rogzites')
    ]);
    
    return redirect('/fooldal')->with('success', 'Költség sikeresen hozzáadva!');
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

    $tranzakcio = Tranzakcio::find($id);

    if (!$tranzakcio) {
        return redirect('/fooldal')->withErrors(['error' => 'A költség nem található!']);
    }

    // Ellenőrzés: csak a saját költségét lehet szerkeszteni
    if ($tranzakcio->felhasznaloid !== session('user')->id) {
        return redirect('/fooldal')->withErrors(['error' => 'Nincs jogod ezt a költséget szerkeszteni!']);
    }

    $kategoriaNev = trim(request('kategoria'));
    $penznemNev = trim(request('penznem'));
    $userId = session('user')->id;

    // Kategória keresése (felhasználó-specifikus vagy globális)
    $kat = kategoria::where('nev', $kategoriaNev)
        ->where(function($q) use ($userId) {
            $q->where('felhasznaloid', $userId)->orWhereNull('felhasznaloid');
        })->first();

    // Ha nem találjuk, létrehozunk egy felhasználóspecifikus kategóriát
    if (!$kat) {
        $kat = kategoria::create([
            'felhasznaloid' => $userId,
            'nev' => $kategoriaNev
        ]);
    }

    // Pénznem keresése (case-insensitive keresés)
    $penznemRecord = penznem::whereRaw('LOWER(nev) = LOWER(?)', [$penznemNev])->first();
    if (!$penznemRecord) {
        return back()->withErrors(['penznem' => 'A megadott pénznem nem létezik az adatbázisban.']);
    }

    $tranzakcio->update([
        'kategoriaid' => $kat->id,
        'penznemid' => $penznemRecord->id,
        'osszeg' => request('osszeg'),
        'megjegyzes' => request('megjegyzes'),
        'rogzites' => request('rogzites')
    ]);

    return redirect('/fooldal')->with('success', 'Költség sikeresen szerkesztve!');
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

    return redirect('/fooldal')->with('success', 'Költség sikeresen törölve!');
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
        // Csak az ArfbazisSeeder futtatása
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'ArfbazisSeeder', '--force' => true]);
        return response()->json(['success' => true, 'message' => 'Árfolyam seeder sikeresen futtatva!']);
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