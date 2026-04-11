@extends('layouts.ui')

@section('title', 'Főoldal - Költség Követő')
@section('body_class', 'page-body')

@section('content')
    @php
        $monthValues = $availableMonths->values();
        $currentMonthIndex = $monthValues->search($selectedMonth);
        $prevMonth = ($currentMonthIndex !== false && $currentMonthIndex < ($monthValues->count() - 1))
            ? $monthValues[$currentMonthIndex + 1]
            : null;
        $nextMonth = ($currentMonthIndex !== false && $currentMonthIndex > 0)
            ? $monthValues[$currentMonthIndex - 1]
            : null;

        $userName = session('user')->nev;
        $initials = collect(preg_split('/\s+/u', trim($userName)))
            ->filter()
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        $customCategoryCount = collect($koltsegKategoriak)
            ->merge($bevetelKategoriak)
            ->filter(fn ($category) => !empty($category->felhasznaloid))
            ->unique('id')
            ->count();

        $expenseCount = $tranzakciokAtvalasztva
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'koltseg'))
            ->count();

        $incomeCount = $tranzakciokAtvalasztva
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'bevetel'))
            ->count();

        $latestTransaction = $tranzakciokAtvalasztva->first();
        $largestExpenseTransaction = $tranzakciokAtvalasztva
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'koltseg'))
            ->sortByDesc(fn ($transaction) => $transaction->osszeghuf ?? $transaction->osszeg ?? 0)
            ->first();
        $largestIncomeTransaction = $tranzakciokAtvalasztva
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'bevetel'))
            ->sortByDesc(fn ($transaction) => $transaction->osszeghuf ?? $transaction->osszeg ?? 0)
            ->first();
        $dominantCurrency = $byCurrency
            ? $byCurrency->sortByDesc(fn ($currency) => abs($currency->total ?? 0))->first()
            : null;

        $monthlyStatusCopy = $tranzakciokAtvalasztva->count() > 0
            ? (($balanceTotal >= 0)
                ? 'A hónap jelenleg pozitív tartományban van, a bevételek fedezik a kiadásokat.'
                : 'A hónap jelenleg negatív tartományban van, ezért érdemes a nagyobb kiadásokat külön figyelni.')
            : 'Még nincs rögzített tétel ebben az időszakban, ezért most a következő bejegyzés lesz az első kapaszkodó.';
    @endphp

    <div class="page-shell">
        <div class="workspace-grid">
            <aside class="sidebar-sticky">
                <section class="window fooldal-sidebar-window">
                    <div class="window-header">
                        @include('partials.window_controls')
                        <div class="window-title-group">
                            <span class="window-title">Sidebar</span>
                            <span class="window-subtitle">Gyors navigáció és profilblokk</span>
                        </div>
                    </div>

                    <div class="window-body control-stack">
                        <div class="profile-card">
                            <div class="profile-head">
                                <div class="avatar">{{ $initials !== '' ? $initials : 'KK' }}</div>
                                <div>
                                    <span class="section-kicker">Aktív profil</span>
                                    <h2 class="section-title" style="margin-top: 8px;">{{ $userName }}</h2>
                                </div>
                            </div>

                        </div>

                        <nav class="nav-list">
                            <a href="/fooldal" class="nav-link active">
                                <span>Áttekintés</span>
                                <span class="nav-badge">⌂</span>
                            </a>
                            <button type="button" class="nav-link nav-link-button" onclick="openModal()">
                                <span>Új tranzakció</span>
                                <span class="nav-badge">+</span>
                            </button>
                            <a href="/statisztika" class="nav-link">
                                <span>Statisztika</span>
                                <span class="nav-badge">↗</span>
                            </a>
                            <a href="/logout" class="nav-link">
                                <span>Kijelentkezés</span>
                                <span class="nav-badge">⎋</span>
                            </a>
                        </nav>

                        <div class="summary-card">
                            <span class="section-kicker">Havi állapot</span>
                            <strong>{{ $selectedMonthLabel }}</strong>
                            <div class="detail-list">
                                <div class="detail-row">
                                    <span>Tranzakció</span>
                                    <strong>{{ $tranzakciokAtvalasztva->count() }}</strong>
                                </div>
                                <div class="detail-row">
                                    <span>Saját kategória</span>
                                    <strong>{{ $customCategoryCount }}</strong>
                                </div>
                                <div class="detail-row">
                                    <span>Aktív pénznem</span>
                                    <strong>{{ $byCurrency->count() }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </aside>

            <main class="main-stack">
                @include('partials.flash_messages')

                <section class="window fooldal-overview-window">
                    <div class="window-header">
                        @include('partials.window_controls')
                        <div class="window-title-group">
                            <span class="window-title">Monthly Overview</span>
                            <span class="window-subtitle">Rövid helyzetkép és kiemelt havi mozgások</span>
                        </div>
                    </div>

                    <div class="window-body hero-layout">
                        <div class="hero-copy overview-copy">
                            <span class="section-kicker">Aktuális időszak</span>
                            <h1 class="hero-title">{{ $selectedMonthLabel }}</h1>
                            <p class="section-copy">
                                {{ $monthlyStatusCopy }}
                            </p>

                            <div class="overview-meta-grid">
                                <article class="mini-card overview-mini-card">
                                    <span class="metric-label">Rögzített tétel</span>
                                    <strong class="overview-mini-value">{{ $tranzakciokAtvalasztva->count() }} db</strong>
                                    <p class="muted-text">{{ $expenseCount }} kiadás és {{ $incomeCount }} bevétel</p>
                                </article>

                                <article class="mini-card overview-mini-card">
                                    <span class="metric-label">Utolsó aktivitás</span>
                                    <strong class="overview-mini-value">
                                        {{ $latestTransaction ? \Carbon\Carbon::parse($latestTransaction->rogzites)->locale('hu')->translatedFormat('F j.') : 'Még nincs adat' }}
                                    </strong>
                                    <p class="muted-text">
                                        {{ $latestTransaction ? ($latestTransaction->kategoria->nev ?? (\App\Models\kategoria::find($latestTransaction->kategoriaid)->nev ?? '-')) : 'Nincs utolsó tranzakció' }}
                                    </p>
                                </article>

                                <article class="mini-card overview-mini-card">
                                    <span class="metric-label">Legaktívabb pénznem</span>
                                    <strong class="overview-mini-value">{{ $dominantCurrency?->currency ?? 'Nincs adat' }}</strong>
                                    <p class="muted-text">
                                        {{ $dominantCurrency ? number_format($dominantCurrency->total, 0, ',', ' ') . ' Ft egyenleg' : 'A bontás a rögzített tételekkel jelenik meg.' }}
                                    </p>
                                </article>

                                <article class="mini-card overview-mini-card">
                                    <span class="metric-label">Saját kategória</span>
                                    <strong class="overview-mini-value">{{ $customCategoryCount }}</strong>
                                    <p class="muted-text">A saját listáid és gyorsan elérhető mentett opciók száma.</p>
                                </article>
                            </div>

                            <div class="hero-actions">
                                <button type="button" class="btn btn-primary" onclick="openModal()">Új tranzakció</button>
                                <a href="/statisztika" class="btn btn-secondary">Részletes statisztika</a>
                            </div>
                        </div>

                        <div class="control-stack overview-side-grid">
                            <div class="metric-grid metric-grid-overview">
                                <article class="metric-card metric-card-overview">
                                    <span class="metric-label">Kiadás</span>
                                    <strong class="metric-value metric-value-money expense">{{ number_format($expenseTotal, 0, ',', ' ') }} Ft</strong>
                                </article>
                                <article class="metric-card metric-card-overview">
                                    <span class="metric-label">Bevétel</span>
                                    <strong class="metric-value metric-value-money income">{{ number_format($incomeTotal, 0, ',', ' ') }} Ft</strong>
                                </article>
                                <article class="metric-card metric-card-overview">
                                    <span class="metric-label">Pénzáramlás</span>
                                    <strong class="metric-value metric-value-money balance {{ $balanceTotal >= 0 ? 'income' : 'expense' }}">
                                        {{ number_format($balanceTotal, 0, ',', ' ') }} Ft
                                    </strong>
                                </article>
                                <article class="metric-card metric-card-overview">
                                    <span class="metric-label">Saját kategória</span>
                                    <strong class="metric-value metric-value-compact">{{ $customCategoryCount }}</strong>
                                </article>
                            </div>

                            <div class="summary-list summary-list-overview">
                                <article class="summary-card summary-card-compact">
                                    <span class="section-kicker">Legnagyobb kiadás</span>
                                    @if($largestExpenseTransaction)
                                        @php
                                            $largestExpenseCategory = $largestExpenseTransaction->kategoria->nev ?? (\App\Models\kategoria::find($largestExpenseTransaction->kategoriaid)->nev ?? '-');
                                        @endphp
                                        <strong>{{ $largestExpenseCategory }}</strong>
                                        <div class="detail-row">
                                            <span>{{ \Carbon\Carbon::parse($largestExpenseTransaction->rogzites)->locale('hu')->translatedFormat('F j.') }}</span>
                                            <strong class="tone-expense">
                                                -{{ number_format($largestExpenseTransaction->osszeg, 2, ',', ' ') }} {{ $largestExpenseTransaction->penznem->nev }}
                                            </strong>
                                        </div>
                                    @else
                                        <strong>Nincs kiadás</strong>
                                        <p class="muted-text">Ebben a hónapban még nincs rögzített költség.</p>
                                    @endif
                                </article>

                                <article class="summary-card summary-card-compact">
                                    <span class="section-kicker">Legnagyobb bevétel</span>
                                    @if($largestIncomeTransaction)
                                        @php
                                            $largestIncomeCategory = $largestIncomeTransaction->kategoria->nev ?? (\App\Models\kategoria::find($largestIncomeTransaction->kategoriaid)->nev ?? '-');
                                        @endphp
                                        <strong>{{ $largestIncomeCategory }}</strong>
                                        <div class="detail-row">
                                            <span>{{ \Carbon\Carbon::parse($largestIncomeTransaction->rogzites)->locale('hu')->translatedFormat('F j.') }}</span>
                                            <strong class="tone-income">
                                                +{{ number_format($largestIncomeTransaction->osszeg, 2, ',', ' ') }} {{ $largestIncomeTransaction->penznem->nev }}
                                            </strong>
                                        </div>
                                    @else
                                        <strong>Nincs bevétel</strong>
                                        <p class="muted-text">Ebben a hónapban még nincs rögzített bevétel.</p>
                                    @endif
                                </article>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="dashboard-main-grid">
                    <section class="window">
                        <div class="window-header">
                            @include('partials.window_controls')
                            <div class="window-title-group">
                                <span class="window-title">Transactions</span>
                                <span class="window-subtitle">Napi tételek szerkeszthető listája</span>
                            </div>
                            <div class="window-actions">
                                <button type="button" class="btn btn-secondary btn-small" onclick="openModal()">+ Új</button>
                            </div>
                        </div>

                        <div class="window-toolbar">
                            <div>
                                <span class="section-kicker">Aktív lista</span>
                                <h2 class="section-title" style="margin-top: 8px;">{{ $selectedMonthLabel }}</h2>
                            </div>

                            @if($availableMonths->count() > 0)
                                <div class="month-switcher">
                                    <a class="month-nav {{ $prevMonth ? '' : 'disabled' }}" href="{{ $prevMonth ? '/fooldal?honap=' . $prevMonth : '#' }}" aria-label="Előző hónap">‹</a>
                                    <span class="month-label">{{ $selectedMonthLabel }}</span>
                                    <a class="month-nav {{ $nextMonth ? '' : 'disabled' }}" href="{{ $nextMonth ? '/fooldal?honap=' . $nextMonth : '#' }}" aria-label="Következő hónap">›</a>
                                </div>
                            @endif
                        </div>

                        <div class="window-body">
                            @if($tranzakciokAtvalasztva->count() > 0)
                                <div class="transaction-stream">
                                    @foreach($tranzakciokAtvalasztva as $item)
                                        @php
                                            $isIncome = (($item->tipus ?? 'koltseg') === 'bevetel');
                                            $categoryName = $item->kategoria->nev ?? (\App\Models\kategoria::find($item->kategoriaid)->nev ?? '-');
                                            $displayDate = \Carbon\Carbon::parse($item->rogzites);
                                            $formattedAmount = number_format($item->osszeg, 2, ',', ' ');
                                            $formattedDeleteAmount = $formattedAmount . ' ' . $item->penznem->nev;
                                        @endphp

                                        <article class="transaction-card">
                                            <div class="transaction-date">
                                                <span class="transaction-day">{{ $displayDate->format('d') }}</span>
                                                <span class="transaction-month">{{ $displayDate->locale('hu')->translatedFormat('M') }}</span>
                                            </div>

                                            <div class="transaction-main">
                                                <div class="transaction-heading">
                                                    <div class="transaction-heading-copy">
                                                        <div class="transaction-heading-top">
                                                            <h3 class="transaction-title">{{ $categoryName }}</h3>
                                                            <div class="transaction-meta">
                                                            <span class="type-badge {{ $isIncome ? 'type-income' : 'type-expense' }}">{{ $isIncome ? 'Bevétel' : 'Költség' }}</span>
                                                                <span class="badge">{{ $item->penznem->nev }}</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="transaction-amount">
                                                        <strong class="amount-primary {{ $isIncome ? 'amount-income' : 'amount-expense' }}">
                                                            {{ $isIncome ? '+' : '-' }}{{ $formattedAmount }} {{ $item->penznem->nev }}
                                                        </strong>

                                                        @if(($item->penznem->nev ?? null) !== 'HUF' && $item->osszeghuf)
                                                            <span class="amount-secondary {{ $isIncome ? 'amount-income' : 'amount-expense' }}">
                                                                {{ $isIncome ? '+' : '-' }}{{ number_format($item->osszeghuf, 0, ',', ' ') }} Ft
                                                            </span>
                                                        @else
                                                            <span class="amount-secondary">Forint alapú rögzítés</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <p class="transaction-note">
                                                    {{ $item->megjegyzes ?: 'Nincs külön megjegyzés ehhez a tételhez.' }}
                                                </p>

                                                <div class="transaction-footer">
                                                    <div class="transaction-caption">
                                                        <span class="micro-pill">{{ $displayDate->locale('hu')->translatedFormat('Y. F j.') }}</span>
                                                        @if(($item->penznem->nev ?? null) !== 'HUF' && $item->osszeghuf)
                                                            <span class="micro-pill">Átváltva HUF-ra</span>
                                                        @endif
                                                    </div>

                                                    <div class="action-set">
                                                        <button
                                                            type="button"
                                                            class="btn btn-secondary btn-small"
                                                            onclick='editTranzakcio({{ $item->id }}, @js($item->rogzites), @js($categoryName), @js($formattedAmount), @js($item->penznem->nev), @js($item->tipus ?? "koltseg"), @js($item->megjegyzes))'
                                                        >
                                                            Szerkesztés
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-danger btn-small"
                                                            onclick='deleteTranzakcio({{ $item->id }}, @js($categoryName), @js($formattedDeleteAmount))'
                                                        >
                                                            Törlés
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @elseif($availableMonths->count() > 0)
                                <div class="empty-state centered">
                                    <h3 class="section-title">Ebben a hónapban még nincs tranzakció.</h3>
                                    <p class="muted-text">Indítsd a listát egy új bejegyzéssel, és máris láthatóvá válik a havi mozgás.</p>
                                    <button type="button" class="btn btn-primary" onclick="openModal()">Első tranzakció hozzáadása</button>
                                </div>
                            @else
                                <div class="empty-state centered">
                                    <h3 class="section-title">Még nincs rögzített tétel.</h3>
                                    <p class="muted-text">Kezdd el a felület használatát egy új költség vagy bevétel felvitelével.</p>
                                    <button type="button" class="btn btn-primary" onclick="openModal()">Új tranzakció</button>
                                </div>
                            @endif
                        </div>
                    </section>

                    <aside class="sticky-card">
                        <section class="window">
                            <div class="window-header">
                                @include('partials.window_controls')
                                <div class="window-title-group">
                                    <span class="window-title">Control Center</span>
                                    <span class="window-subtitle">Havi gyorsösszegzés és pénznem bontás</span>
                                </div>
                            </div>

                            <div class="window-body control-stack">
                                <div class="summary-list">
                                    <article class="summary-card">
                                        <span class="section-kicker">Összes kiadás</span>
                                        <strong class="summary-value tone-expense">{{ number_format($expenseTotal, 0, ',', ' ') }} Ft</strong>
                                        <p class="muted-text">Forint alapú összesítés a kiválasztott időszakra.</p>
                                    </article>

                                    <article class="summary-card">
                                        <span class="section-kicker">Összes bevétel</span>
                                        <strong class="summary-value tone-income">{{ number_format($incomeTotal, 0, ',', ' ') }} Ft</strong>
                                        <p class="muted-text">Minden pénznem átszámolva és egy helyen kezelve.</p>
                                    </article>

                                    <article class="summary-card">
                                        <span class="section-kicker">Pénzáramlás</span>
                                        <strong class="summary-value {{ $balanceTotal >= 0 ? 'tone-income' : 'tone-expense' }}">
                                            {{ number_format($balanceTotal, 0, ',', ' ') }} Ft
                                        </strong>
                                        <p class="muted-text">Pozitív értéknél többlet, negatívnál havi mínusz.</p>
                                    </article>
                                </div>

                                <div>
                                    <span class="section-kicker">Pénznem bontás</span>
                                    @if($byCurrency && $byCurrency->count() > 0)
                                        <div class="currency-list" style="margin-top: 14px;">
                                            @foreach($byCurrency as $currency)
                                                <article class="currency-card">
                                                    <div class="currency-header">
                                                        <div>
                                                            <span class="currency-code">{{ $currency->currency }}</span>
                                                            <h3 class="transaction-title" style="margin-top: 8px;">{{ $currency->currency }}</h3>
                                                        </div>
                                                        <strong class="currency-balance {{ $currency->total >= 0 ? 'tone-income' : 'tone-expense' }}">
                                                            {{ number_format($currency->native_total, 2, ',', ' ') }} {{ $currency->currency }}
                                                        </strong>
                                                    </div>

                                                    <div class="detail-list">
                                                        <div class="currency-row">
                                                            <span>Kiadás</span>
                                                            <strong class="tone-expense">{{ number_format($currency->native_expense, 2, ',', ' ') }} {{ $currency->currency }}</strong>
                                                        </div>
                                                        <div class="currency-row">
                                                            <span>Bevétel</span>
                                                            <strong class="tone-income">{{ number_format($currency->native_income, 2, ',', ' ') }} {{ $currency->currency }}</strong>
                                                        </div>
                                                        @if($currency->currency !== 'HUF')
                                                            <div class="currency-row">
                                                                <span>Forint egyenleg</span>
                                                                <strong>{{ number_format($currency->total, 0, ',', ' ') }} Ft</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-state centered" style="margin-top: 14px;">
                                            <h3 class="section-title">Nincs még bontás</h3>
                                            <p class="muted-text">Az első rögzített tétel után itt jelennek meg a pénznemek.</p>
                                        </div>
                                    @endif
                                </div>

                                <a href="/statisztika" class="btn btn-secondary">Részletes statisztika megnyitása</a>
                            </div>
                        </section>
                    </aside>
                </div>
            </main>
        </div>
    </div>

    <div id="koltsegModal" class="modal">
        <div class="modal-content">
            <div class="window-header">
                @include('partials.window_controls')
                <div class="window-title-group">
                    <span id="modalTitle" class="window-title">Új tranzakció hozzáadása</span>
                    <span class="window-subtitle">Költség vagy bevétel rögzítése, kategóriával és dátummal</span>
                </div>
                <button type="button" class="close-btn" onclick="closeModal()" aria-label="Bezárás">×</button>
            </div>

            <div class="window-body">
                @if($errors->any())
                    <div class="flash-stack" style="margin-bottom: 18px;">
                        @foreach($errors->all() as $error)
                            <div class="flash flash-danger">{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="/koltseg/add" method="POST" id="koltsegForm" class="form-grid">
                    @csrf

                    <div class="type-toggle" role="group" aria-label="Tranzakció típusa">
                        <input type="hidden" name="tipus" id="tipus_input" class="type-toggle-input" value="{{ old('tipus', 'koltseg') }}" required>
                        <button type="button" class="type-toggle-option" data-value="koltseg" onclick="setTipus('koltseg')" aria-pressed="false">Költség</button>
                        <button type="button" class="type-toggle-option" data-value="bevetel" onclick="setTipus('bevetel')" aria-pressed="false">Bevétel</button>
                    </div>

                    <div class="form-grid two-columns">
                        <div class="field-group span-2">
                            <label class="field-label" for="kategoria_input">Kategória</label>
                            <div class="kategoria-input-wrapper" id="kategoria_wrapper">
                                <input
                                    class="field-control"
                                    type="text"
                                    id="kategoria_input"
                                    name="kategoria"
                                    placeholder="Például bevásárlás vagy fizetés"
                                    value="{{ old('kategoria') }}"
                                    required
                                    oninput="filterKategoriak()"
                                    onclick="filterKategoriak(true)"
                                    onblur="ensureCategorySaved()"
                                >
                                <div id="kategoria_list" class="kategoria-list"></div>
                                <div id="kategoria_message" class="field-inline-message"></div>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="osszeg_input">Összeg</label>
                            <input class="field-control" id="osszeg_input" type="text" name="osszeg" value="{{ old('osszeg') }}" placeholder="0,00" required>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="penznem_input">Pénznem</label>
                            <div class="kategoria-input-wrapper" id="penznem_wrapper">
                                <input
                                    class="field-control"
                                    type="text"
                                    id="penznem_input"
                                    name="penznem"
                                    placeholder="HUF"
                                    value="{{ old('penznem', 'HUF') }}"
                                    required
                                    oninput="filterPenznemek()"
                                    onclick="document.getElementById('penznem_list').classList.add('show')"
                                >
                                <div id="penznem_list" class="kategoria-list">
                                    @foreach($penznemek as $penznem)
                                        <div class="kategoria-item penznem-item" onclick="selectPenznem('{{ $penznem->nev }}')">{{ $penznem->nev }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="field-group span-2">
                            <label class="field-label" for="datePickerTrigger">Rögzítés dátuma</label>
                            <div class="date-picker" id="date_picker">
                                <input type="hidden" id="rogzites_input" name="rogzites" value="{{ old('rogzites', now()->toDateString()) }}" required>
                                <button
                                    type="button"
                                    class="field-control date-picker-trigger"
                                    id="datePickerTrigger"
                                    onclick="toggleDatePicker()"
                                    aria-haspopup="dialog"
                                    aria-expanded="false"
                                    aria-controls="datePickerPopover"
                                >
                                    <span id="datePickerValue">Válassz dátumot</span>
                                    <span class="date-picker-trigger-icon" aria-hidden="true"></span>
                                </button>

                                <div class="date-picker-popover" id="datePickerPopover" aria-hidden="true">
                                    <div class="date-picker-header">
                                        <button type="button" class="date-picker-nav" onclick="changeCalendarMonth(-1)" aria-label="Előző hónap">‹</button>
                                        <div class="date-picker-month" id="datePickerMonth"></div>
                                        <button type="button" class="date-picker-nav" onclick="changeCalendarMonth(1)" aria-label="Következő hónap">›</button>
                                    </div>

                                    <div class="date-picker-weekdays">
                                        <span class="date-picker-weekday">H</span>
                                        <span class="date-picker-weekday">K</span>
                                        <span class="date-picker-weekday">Sze</span>
                                        <span class="date-picker-weekday">Cs</span>
                                        <span class="date-picker-weekday">P</span>
                                        <span class="date-picker-weekday">Szo</span>
                                        <span class="date-picker-weekday">V</span>
                                    </div>

                                    <div class="date-picker-grid" id="datePickerGrid"></div>

                                    <div class="date-picker-actions">
                                        <button type="button" class="date-picker-action" onclick="closeDatePicker()">Bezárás</button>
                                        <button type="button" class="date-picker-action date-picker-action-primary" onclick="jumpToToday()">Ma</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="field-group span-2">
                            <label class="field-label" for="megjegyzes_input">Megjegyzés</label>
                            <textarea class="field-control" id="megjegyzes_input" name="megjegyzes" placeholder="Rövid leírás vagy kontextus">{{ old('megjegyzes') }}</textarea>
                        </div>

                        <div class="field-group span-2">
                            <button type="submit" class="btn btn-primary">Tranzakció hozzáadása</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteConfirmModal" class="modal">
        <div class="modal-content confirm-modal-content">
            <div class="window-header">
                @include('partials.window_controls')
                <div class="window-title-group">
                    <span class="window-title">Törlés megerősítése</span>
                    <span class="window-subtitle">A művelet nem visszavonható</span>
                </div>
                <button type="button" class="close-btn" onclick="closeDeleteConfirm()" aria-label="Bezárás">×</button>
            </div>

            <div class="window-body">
                <div class="confirm-meta">
                    <div class="confirm-meta-row">
                        <span>Kategória</span>
                        <span class="confirm-meta-value" id="deleteConfirmCategory">-</span>
                    </div>
                    <div class="confirm-meta-row">
                        <span>Összeg</span>
                        <span class="confirm-meta-value" id="deleteConfirmAmount">-</span>
                    </div>
                </div>

                <div class="confirm-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteConfirm()">Mégse</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDeleteTranzakcio()">Törlés</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('partials.dashboard_scripts')
@endpush
