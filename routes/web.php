<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Carbon\Carbon;
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

function defaultCategoryNamesByType(string $type): array
{
    return $type === 'bevetel'
        ? [
            'fizetés',
            'bónusz',
            'ajándék',
            'eladás',
            'kamat',
            'ösztöndíj',
        ]
        : [
            'bevásárlás',
            'szórakozás',
            'vendéglátás',
            'egészség',
            'ruházat',
            'közlekedés',
            'befektetés',
        ];
}

function getSuggestedCategoriesByType(int $userId, string $type)
{
    $normalizedType = resolveTransactionType($type) ?? 'koltseg';
    $hasTransactionTypeColumn = ensureTipusColumnExists();
    $hasCategoryTypeColumn = ensureCategoryTypeColumnExists();
    $usedCategoryIds = $hasTransactionTypeColumn
        ? Tranzakcio::where('felhasznaloid', $userId)
            ->where('tipus', $normalizedType)
            ->pluck('kategoriaid')
            ->filter()
            ->unique()
            ->values()
        : collect();

    $databaseCategories = kategoria::query()
        ->where(function ($query) use ($userId) {
            $query->where('felhasznaloid', $userId)->orWhereNull('felhasznaloid');
        })
        ->when($hasCategoryTypeColumn, function ($query) use ($normalizedType, $usedCategoryIds) {
            $query->where(function ($innerQuery) use ($normalizedType, $usedCategoryIds) {
                $innerQuery->where('tipus', $normalizedType)
                    ->orWhereNull('tipus');

                if ($usedCategoryIds->isNotEmpty()) {
                    $innerQuery->orWhereIn('id', $usedCategoryIds);
                }
            });
        })
        ->get();

    $defaultCategories = collect(defaultCategoryNamesByType($normalizedType))->map(function ($nev) {
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

function findOrCreateCategory(string $kategoriaNev, int $userId, ?string $type = null): kategoria
{
    $trimmedName = trim($kategoriaNev);
    $normalizedType = resolveTransactionType($type);
    $hasCategoryTypeColumn = ensureCategoryTypeColumnExists();

    $existingCategory = kategoria::whereRaw('LOWER(TRIM(nev)) = LOWER(?)', [$trimmedName])
        ->where(function ($q) use ($userId) {
            $q->where('felhasznaloid', $userId)->orWhereNull('felhasznaloid');
        })
        ->when($hasCategoryTypeColumn && $normalizedType, function ($query) use ($normalizedType) {
            $query->where(function ($innerQuery) use ($normalizedType) {
                $innerQuery->where('tipus', $normalizedType)->orWhereNull('tipus');
            });
        })
        ->orderByRaw('CASE WHEN felhasznaloid IS NULL THEN 1 ELSE 0 END')
        ->when($hasCategoryTypeColumn && $normalizedType, function ($query) use ($normalizedType) {
            $query->orderByRaw('CASE WHEN tipus = ? THEN 0 WHEN tipus IS NULL THEN 1 ELSE 2 END', [$normalizedType]);
        })
        ->first();

    if ($existingCategory) {
        return $existingCategory;
    }

    return kategoria::create([
        'felhasznaloid' => $userId,
        'nev' => $trimmedName,
        ...($hasCategoryTypeColumn && $normalizedType ? ['tipus' => $normalizedType] : []),
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

function ensureCategoryTypeColumnExists(): bool
{
    try {
        return Schema::hasColumn('kategoria', 'tipus');
    } catch (\Throwable $e) {
        return false;
    }
}

function ensureEmailVerificationColumnsExist(): bool
{
    try {
        $needsSchemaChange = false;
        $hasEmailVerifiedAt = Schema::hasColumn('felhasznalo', 'email_verified_at');
        $hasVerificationToken = Schema::hasColumn('felhasznalo', 'verification_token');
        $hasVerificationSentAt = Schema::hasColumn('felhasznalo', 'verification_sent_at');

        if (!$hasEmailVerifiedAt || !$hasVerificationToken || !$hasVerificationSentAt) {
            $needsSchemaChange = true;
        }

        if ($needsSchemaChange) {
            Schema::table('felhasznalo', function (\Illuminate\Database\Schema\Blueprint $table) use ($hasEmailVerifiedAt, $hasVerificationToken, $hasVerificationSentAt) {
                if (!$hasEmailVerifiedAt) {
                    $table->timestamp('email_verified_at')->nullable();
                }
                if (!$hasVerificationToken) {
                    $table->string('verification_token', 100)->nullable();
                }
                if (!$hasVerificationSentAt) {
                    $table->timestamp('verification_sent_at')->nullable();
                }
            });
        }

        return Schema::hasColumn('felhasznalo', 'email_verified_at')
            && Schema::hasColumn('felhasznalo', 'verification_token')
            && Schema::hasColumn('felhasznalo', 'verification_sent_at');
    } catch (\Throwable $e) {
        return false;
    }
}

function sendVerificationEmail(felhasznalo $user): bool
{
    try {
        $plainToken = Str::random(64);
        $user->verification_token = hash('sha256', $plainToken);
        $user->verification_sent_at = now();
        $user->save();

        $verifyUrl = URL::temporarySignedRoute(
            'email.verify',
            now()->addHours(24),
            [
                'token' => $plainToken,
                'email' => $user->email,
            ]
        );

        Mail::raw(
            "Szia {$user->nev}!\n\nKérlek erősítsd meg az email címed az alábbi linkre kattintva:\n{$verifyUrl}\n\nA link biztonsági okból 24 óráig érvényes.\n\nHa nem te regisztráltál, hagyd figyelmen kívül ezt az üzenetet.",
            function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Email megerősítés - Költség Követő');
            }
        );

        return true;
    } catch (\Throwable $e) {
        Log::error('Verification email send failed.', [
            'email' => $user->email,
            'error' => $e->getMessage(),
        ]);
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
    $verificationEnabled = ensureEmailVerificationColumnsExist();

    $user = felhasznalo::where('email', request('email'))->first();
    
    if ($user && Hash::check(request('password'), $user->password)) {
        if ($verificationEnabled && empty($user->email_verified_at)) {
            return back()
                ->withErrors(['A bejelentkezéshez előbb erősítsd meg az email címed.'])
                ->withInput(['email' => request('email')])
                ->with('pending_verification_email', $user->email);
        }

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
    $verificationEnabled = ensureEmailVerificationColumnsExist();
    $hasTipusColumn = ensureTipusColumnExists();
    $currentUser = felhasznalo::find(session('user')->id);
    if (!$currentUser) {
        session()->flush();
        return redirect('/login')->withErrors(['A munkamenet lejárt, jelentkezz be újra.']);
    }
    if ($verificationEnabled && empty($currentUser->email_verified_at)) {
        session()->flush();
        return redirect('/login')
            ->withErrors(['A fiókod még nincs megerősítve. Előbb igazold az email címed.'])
            ->with('pending_verification_email', $currentUser->email);
    }
    $userId = $currentUser->id;

    $exchangeRateService = new ExchangeRateService();

    $tranzakciok = Tranzakcio::with('kategoria', 'penznem')
        ->where('felhasznaloid', $userId)
        ->orderByDesc('rogzites')
        ->get();
    
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

    $availableMonths = $tranzakciokAtvalasztva
        ->map(function ($t) {
            try {
                return Carbon::parse($t->rogzites)->format('Y-m');
            } catch (\Throwable $e) {
                return null;
            }
        })
        ->filter()
        ->unique()
        ->sortDesc()
        ->values();

    $currentMonth = Carbon::now()->format('Y-m');
    $selectedMonth = request('honap');

    if (!preg_match('/^\d{4}-\d{2}$/', (string) $selectedMonth) || !$availableMonths->contains($selectedMonth)) {
        $selectedMonth = $availableMonths->contains($currentMonth)
            ? $currentMonth
            : ($availableMonths->first() ?? $currentMonth);
    }

    $tranzakciokAtvalasztva = $tranzakciokAtvalasztva
        ->filter(function ($t) use ($selectedMonth) {
            try {
                return Carbon::parse($t->rogzites)->format('Y-m') === $selectedMonth;
            } catch (\Throwable $e) {
                return false;
            }
        })
        ->sortByDesc('rogzites')
        ->values();

    $selectedMonthLabel = Carbon::createFromFormat('Y-m', $selectedMonth)
        ->locale('hu')
        ->translatedFormat('Y. F');
    
    $koltsegKategoriak = getSuggestedCategoriesByType($userId, 'koltseg');
    $bevetelKategoriak = getSuggestedCategoriesByType($userId, 'bevetel');
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
    $byCurrency = $tranzakciokAtvalasztva
        ->groupBy(function ($item) {
            return $item->penznem->nev ?? 'HUF';
        })
        ->map(function ($items, $currency) {
            $nativeIncome = $items
                ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
                ->sum('osszeg');

            $nativeExpense = $items
                ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
                ->sum('osszeg');

            $income = $items
                ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
                ->sum('osszeghuf');

            $expense = $items
                ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
                ->sum('osszeghuf');

            return (object) [
                'currency' => $currency,
                'native_income' => $nativeIncome,
                'native_expense' => $nativeExpense,
                'native_total' => $nativeIncome - $nativeExpense,
                'income' => $income,
                'expense' => $expense,
                'total' => $income - $expense,
            ];
        })
        ->sortByDesc('total')
        ->values();

    return view('fooldal', compact(
        'tranzakciokAtvalasztva',
        'koltsegKategoriak',
        'bevetelKategoriak',
        'penznemek',
        'expenseTotal',
        'incomeTotal',
        'balanceTotal',
        'byCurrency',
        'arfolyamok',
        'tranzakciok',
        'hasTipusColumn',
        'availableMonths',
        'selectedMonth',
        'selectedMonthLabel'
    ));
});

Route::get('/teszt', function () {
    $felhasznalo = felhasznalo::all();
    return view('teszt', ['felhasznalo' => $felhasznalo]);
});

Route::get('/felhasznalo/add', function () {
    return view('felhasznalo_hozzaadas');
});

Route::post('/felhasznalo/add', function () {
    $verificationEnabled = ensureEmailVerificationColumnsExist();

    $validated = request()->validate(
        [
            'nev' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:felhasznalo,email'],
            'password' => ['required', 'string', 'min:6'],
            'telefonszam' => ['nullable', 'string', 'max:15', 'unique:felhasznalo,telefon'],
            'orszag' => ['nullable', 'string', 'max:255'],
            'telepules' => ['nullable', 'string', 'max:255'],
        ],
        [
            'nev.required' => 'A nev megadasa kotelezo.',
            'email.required' => 'Az email megadasa kotelezo.',
            'email.email' => 'Ervenyes email cimet adj meg.',
            'email.unique' => 'Ez az email cim mar regisztralva van.',
            'password.required' => 'A jelszo megadasa kotelezo.',
            'password.min' => 'A jelszonak legalabb 6 karakternek kell lennie.',
            'telefonszam.unique' => 'Ez a telefonszam mar hasznalatban van.',
        ]
    );

    $phone = trim((string) ($validated['telefonszam'] ?? ''));

    $user = felhasznalo::create([
        'nev' => trim($validated['nev']),
        'email' => strtolower(trim($validated['email'])),
        'password' => Hash::make($validated['password']),
        'telefon' => $phone === '' ? null : $phone,
        'orszag' => isset($validated['orszag']) ? trim((string) $validated['orszag']) : null,
        'telepules' => isset($validated['telepules']) ? trim((string) $validated['telepules']) : null,
    ]);

    if (!$verificationEnabled) {
        return redirect('/login')->with('success', 'Regisztráció sikeres! Jelentkezz be!');
    }

    $emailSent = sendVerificationEmail($user);

    if ($emailSent) {
        return redirect('/login')->with('success', 'Regisztráció sikeres! Küldtünk egy megerősítő emailt.');
    }

    return redirect('/login')
        ->with('success', 'Regisztráció sikeres! Az email küldése nem sikerült, kérj új megerősítő linket.')
        ->with('pending_verification_email', $user->email);
});

Route::get('/email/verify/{token}', function ($token) {
    if (!ensureEmailVerificationColumnsExist()) {
        return redirect('/login')->withErrors(['Az email megerosites most nem erheto el.']);
    }

    if (empty($token)) {
        return redirect('/login')->withErrors(['Ervenytelen megerosito link.']);
    }

    if (!request()->hasValidSignature()) {
        return redirect('/login')->withErrors(['A megerosito link ervenytelen vagy lejart. Kerj uj megerosito emailt.']);
    }

    $email = strtolower(trim((string) request()->query('email')));
    if ($email === '') {
        return redirect('/login')->withErrors(['A megerosito link hianyos. Kerj uj megerosito emailt.']);
    }

    $user = felhasznalo::where('email', $email)->first();
    if (!$user) {
        return redirect('/login')->withErrors(['A megerosito link ervenytelen vagy mar fel lett hasznalva.']);
    }

    if (!empty($user->email_verified_at)) {
        return redirect('/login')->with('success', 'Ez az email cim mar meg lett erositve, bejelentkezhetsz.');
    }

    $storedToken = (string) ($user->verification_token ?? '');
    $tokenHash = hash('sha256', (string) $token);
    $matchesHashedToken = $storedToken !== '' && hash_equals($storedToken, $tokenHash);
    $matchesLegacyToken = $storedToken !== '' && hash_equals($storedToken, (string) $token);

    if (!$matchesHashedToken && !$matchesLegacyToken) {
        return redirect('/login')->withErrors(['A megerosito link ervenytelen vagy mar fel lett hasznalva.']);
    }

    if (!empty($user->verification_sent_at) && Carbon::parse($user->verification_sent_at)->lt(now()->subHours(24))) {
        return redirect('/login')
            ->withErrors(['A megerosito link lejart. Kerj uj megerosito emailt.'])
            ->with('pending_verification_email', $user->email);
    }

    $user->email_verified_at = now();
    $user->verification_token = null;
    $user->save();

    return redirect('/login')->with('success', 'Email cim sikeresen megerositve! Most mar be tudsz jelentkezni.');
})->name('email.verify');

Route::post('/email/verify/resend', function () {
    if (!ensureEmailVerificationColumnsExist()) {
        return back()->withErrors(['Az email megerősítés most nem elérhető.']);
    }

    $email = strtolower(trim((string) request('email')));
    if ($email === '') {
        return back()->withErrors(['Adj meg egy email címet az újraküldéshez.']);
    }

    $user = felhasznalo::where('email', $email)->first();
    if (!$user) {
        return back()->with('success', 'Ha létezik ilyen email, új megerősítő linket küldtünk.');
    }

    if (!empty($user->email_verified_at)) {
        return back()->with('success', 'Ez az email cím már meg van erősítve, bejelentkezhetsz.');
    }

    if (!empty($user->verification_sent_at) && Carbon::parse($user->verification_sent_at)->gt(now()->subMinutes(1))) {
        return back()
            ->withErrors(['Most küldtünk megerősítő emailt. Kérlek várj legalább 1 percet az újraküldésig.'])
            ->with('pending_verification_email', $user->email);
    }


    $emailSent = sendVerificationEmail($user);
    if (!$emailSent) {
        return back()
            ->withErrors(['Az email küldése most nem sikerült, próbáld újra később.'])
            ->with('pending_verification_email', $user->email);
    }

    return back()
        ->with('success', 'Új megerősítő email elküldve.')
        ->with('pending_verification_email', $user->email);
})->middleware('throttle:3,1');

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

    $kat = findOrCreateCategory($kategoriaNev, $userId, $tipus);

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
        return response()->json(['success' => false, 'message' => 'Bejelentkezés szükséges.'], 401);
    }

    $tipus = resolveTransactionType(request('tipus'));
    $kategoriaNev = trim((string) request('kategoria_nev'));

    if ($kategoriaNev === '') {
        return response()->json(['success' => false, 'message' => 'Adj meg egy kategórianevet.'], 422);
    }

    if (!$tipus) {
        return response()->json(['success' => false, 'message' => 'Érvényes tranzakciótípus szükséges.'], 422);
    }

    $kategoria = findOrCreateCategory($kategoriaNev, session('user')->id, $tipus);

    return response()->json([
        'success' => true,
        'kategoriaid' => $kategoria->id,
        'kategoria_nev' => $kategoria->nev,
        'tipus' => $tipus,
        'owned' => (int) $kategoria->felhasznaloid === (int) session('user')->id,
    ]);
});

Route::delete('/kategoria/{id}', function ($id) {
    if (!session('user')) {
        return response()->json(['success' => false, 'message' => 'Bejelentkezés szükséges.'], 401);
    }

    $kategoria = kategoria::find($id);

    if (!$kategoria) {
        return response()->json(['success' => false, 'message' => 'A kategória nem található.'], 404);
    }

    if ((int) $kategoria->felhasznaloid !== (int) session('user')->id) {
        return response()->json(['success' => false, 'message' => 'Csak a saját kategóriádat törölheted.'], 403);
    }

    $hasTransactions = Tranzakcio::where('kategoriaid', $kategoria->id)->exists();

    if ($hasTransactions) {
        return response()->json(['success' => false, 'message' => 'A kategória használatban van, ezért nem törölhető.'], 422);
    }

    $kategoria->delete();

    return response()->json([
        'success' => true,
        'kategoriaid' => (int) $id,
    ]);
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

    $kat = findOrCreateCategory($kategoriaNev, $userId, $tipus);

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
