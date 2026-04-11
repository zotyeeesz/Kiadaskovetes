@extends('layouts.ui')

@section('title', 'Statisztika - Költség Követő')
@section('body_class', 'page-body')

@section('content')
    @php
        $monthValues = ($availableMonths ?? collect())->values();
        $currentMonthIndex = $monthValues->search($selectedMonth ?? null);
        $prevMonth = ($currentMonthIndex !== false && $currentMonthIndex < ($monthValues->count() - 1))
            ? $monthValues[$currentMonthIndex + 1]
            : null;
        $nextMonth = ($currentMonthIndex !== false && $currentMonthIndex > 0)
            ? $monthValues[$currentMonthIndex - 1]
            : null;

        $yearValues = ($availableYears ?? collect())->values();
        $currentYearIndex = $yearValues->search($selectedYear ?? null);
        $prevYear = ($currentYearIndex !== false && $currentYearIndex < ($yearValues->count() - 1))
            ? $yearValues[$currentYearIndex + 1]
            : null;
        $nextYear = ($currentYearIndex !== false && $currentYearIndex > 0)
            ? $yearValues[$currentYearIndex - 1]
            : null;

        $maxExpense = max(1, (float) ($trendData->max('expense') ?? 0));
        $maxIncome = max(1, (float) ($trendData->max('income') ?? 0));
        $maxBalance = max(1, (float) collect($trendData)->map(fn ($item) => abs((float) $item->total))->max());
        $topCategory = $byCategory->first();
        $topCurrency = $byCurrency->first();
    @endphp

    <div class="page-shell">
        <div class="workspace-grid">
            <aside class="sidebar-sticky">
                <section class="window">
                    <div class="window-header">
                        @include('partials.window_controls')
                        <div class="window-title-group">
                            <span class="window-title">Navigation</span>
                            <span class="window-subtitle">Váltás a fő nézetek között</span>
                        </div>
                    </div>

                    <div class="window-body control-stack stats-sidebar">
                        <div class="profile-card">
                            <div>
                                <span class="section-kicker">Aktív időszak</span>
                                <h2 class="section-title">{{ $selectedPeriodLabel }}</h2>
                                <p class="section-copy">
                                    {{ $selectedView === 'eves' ? 'Éves trendeket és bontásokat látsz.' : 'A kiválasztott hónap részletes bontása látható.' }}
                                </p>
                            </div>
                        </div>

                        <nav class="nav-list">
                            <a href="/fooldal" class="nav-link">
                                <span>Főoldal</span>
                                <span class="nav-badge">⌂</span>
                            </a>
                            <a href="/statisztika?nezet=havi&honap={{ $selectedMonth }}" class="nav-link {{ $selectedView === 'havi' ? 'active' : '' }}">
                                <span>Havi nézet</span>
                                <span class="nav-badge">{{ $selectedMonthLabel }}</span>
                            </a>
                            <a href="/statisztika?nezet=eves&ev={{ $selectedYear }}" class="nav-link {{ $selectedView === 'eves' ? 'active' : '' }}">
                                <span>Éves nézet</span>
                                <span class="nav-badge">{{ $selectedYear }}</span>
                            </a>
                            <a href="/logout" class="nav-link">
                                <span>Kijelentkezés</span>
                                <span class="nav-badge">⎋</span>
                            </a>
                        </nav>

                        <div class="segmented-links">
                            <a class="segment-link {{ $selectedView === 'havi' ? 'active' : '' }}" href="/statisztika?nezet=havi&honap={{ $selectedMonth }}">
                                Havi
                            </a>
                            <a class="segment-link {{ $selectedView === 'eves' ? 'active' : '' }}" href="/statisztika?nezet=eves&ev={{ $selectedYear }}">
                                Éves
                            </a>
                        </div>

                        <div class="summary-card">
                            <span class="section-kicker">Léptetés</span>
                            <div class="month-switcher">
                                @if($selectedView === 'eves')
                                    <a class="month-nav {{ $prevYear ? '' : 'disabled' }}" href="{{ $prevYear ? '/statisztika?nezet=eves&ev=' . $prevYear : '#' }}" aria-label="Előző év">‹</a>
                                    <span class="month-label">{{ $selectedYearLabel }}</span>
                                    <a class="month-nav {{ $nextYear ? '' : 'disabled' }}" href="{{ $nextYear ? '/statisztika?nezet=eves&ev=' . $nextYear : '#' }}" aria-label="Következő év">›</a>
                                @else
                                    <a class="month-nav {{ $prevMonth ? '' : 'disabled' }}" href="{{ $prevMonth ? '/statisztika?nezet=havi&honap=' . $prevMonth : '#' }}" aria-label="Előző hónap">‹</a>
                                    <span class="month-label">{{ $selectedMonthLabel }}</span>
                                    <a class="month-nav {{ $nextMonth ? '' : 'disabled' }}" href="{{ $nextMonth ? '/statisztika?nezet=havi&honap=' . $nextMonth : '#' }}" aria-label="Következő hónap">›</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </aside>

            <main class="main-stack">
                <section class="window">
                    <div class="window-header">
                        @include('partials.window_controls')
                        <div class="window-title-group">
                            <span class="window-title">Analytics Overview</span>
                            <span class="window-subtitle">{{ $trendTitle }} a kiválasztott időszak alapján</span>
                        </div>
                    </div>

                    <div class="window-body hero-layout">
                        <div class="hero-copy overview-copy">
                            <span class="section-kicker">Aktív statisztika</span>
                            <h1 class="hero-title">{{ $selectedPeriodLabel }}</h1>
                            <p class="section-copy">
                                {{ $selectedView === 'eves' ? 'Az éves bontás kiemeli a legerősebb hónapokat, a domináns kategóriákat és a pénznemek teljes mozgását.' : 'A havi bontás egy helyen mutatja a teljes kiadást, bevételt, egyenleget és a legerősebb kategóriát.' }}
                            </p>

                            <div class="overview-meta-grid">
                                <article class="mini-card overview-mini-card">
                                    <span class="metric-label">Aktív nézet</span>
                                    <strong class="overview-mini-value">{{ $selectedView === 'eves' ? 'Éves' : 'Havi' }}</strong>
                                    <p class="muted-text">{{ $trendTitle }} a kiválasztott időszak alapján.</p>
                                </article>

                                <article class="mini-card overview-mini-card">
                                    <span class="metric-label">Domináns kategória</span>
                                    <strong class="overview-mini-value">{{ $topCategory->name ?? 'Nincs adat' }}</strong>
                                    <p class="muted-text">
                                        {{ $topCategory ? number_format($topCategory->total ?? 0, 0, ',', ' ') . ' Ft kiadás' : 'Ebben az időszakban még nincs kiugró kategória.' }}
                                    </p>
                                </article>

                                <article class="mini-card overview-mini-card">
                                    <span class="metric-label">Aktív pénznem</span>
                                    <strong class="overview-mini-value">{{ $topCurrency->currency ?? 'Nincs adat' }}</strong>
                                    <p class="muted-text">
                                        {{ $topCurrency ? number_format($topCurrency->total ?? 0, 0, ',', ' ') . ' Ft egyenleg' : 'A bontás a rögzített mozgásokkal jelenik meg.' }}
                                    </p>
                                </article>

                                <article class="mini-card overview-mini-card">
                                    <span class="metric-label">Bontott sorok</span>
                                    <strong class="overview-mini-value">{{ $trendData->count() }}</strong>
                                    <p class="muted-text">{{ $trendFirstColumnLabel }} szerint rendezett összesítés.</p>
                                </article>
                            </div>

                            <div class="hero-actions">
                                <a href="/fooldal" class="btn btn-secondary">Vissza a főoldalra</a>
                                <a href="/logout" class="btn btn-danger">Kijelentkezés</a>
                            </div>
                        </div>

                        <div class="metric-grid metric-grid-overview">
                            <article class="metric-card metric-card-overview">
                                <span class="metric-label">Összes kiadás</span>
                                <strong class="metric-value metric-value-money expense">{{ number_format($expenseTotal ?? 0, 0, ',', ' ') }} Ft</strong>
                            </article>
                            <article class="metric-card metric-card-overview">
                                <span class="metric-label">Összes bevétel</span>
                                <strong class="metric-value metric-value-money income">{{ number_format($incomeTotal ?? 0, 0, ',', ' ') }} Ft</strong>
                            </article>
                            <article class="metric-card metric-card-overview">
                                <span class="metric-label">Pénzáramlás</span>
                                <strong class="metric-value metric-value-money balance {{ ($balanceTotal ?? 0) >= 0 ? 'income' : 'expense' }}">
                                    {{ number_format($balanceTotal ?? 0, 0, ',', ' ') }} Ft
                                </strong>
                            </article>
                            <article class="metric-card metric-card-overview">
                                <span class="metric-label">Legerősebb kategória</span>
                                <strong class="metric-value metric-value-compact">
                                    {{ $topCategory->name ?? 'Nincs adat' }}
                                </strong>
                            </article>
                        </div>
                    </div>
                </section>

                <div class="stats-grid">
                    <section class="window span-2">
                        <div class="window-header">
                            @include('partials.window_controls')
                            <div class="window-title-group">
                                <span class="window-title">{{ $trendTitle }}</span>
                                <span class="window-subtitle">{{ $trendFirstColumnLabel }} szerinti összesített mozgások</span>
                            </div>
                        </div>

                        <div class="window-body">
                            @if($trendData && $trendData->count() > 0)
                                <div class="story-list">
                                    @foreach($trendData as $index => $item)
                                        <article class="story-row">
                                            <div class="story-headline">
                                                <div>
                                                    <span class="story-index">#{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                                    <h3 class="transaction-title" style="margin-top: 8px;">{{ $item->label }}</h3>
                                                </div>
                                                <span class="story-number {{ $item->total >= 0 ? 'tone-income' : 'tone-expense' }}">
                                                    {{ number_format($item->total, 0, ',', ' ') }} Ft
                                                </span>
                                            </div>

                                            <div class="control-stack">
                                                <div>
                                                    <div class="story-meta">
                                                        <span>Kiadás</span>
                                                        <span>{{ number_format($item->expense, 0, ',', ' ') }} Ft</span>
                                                    </div>
                                                    <div class="progress-bar">
                                                        <span class="progress-fill expense" style="width: {{ max(6, min(100, round(($item->expense / $maxExpense) * 100))) }}%;"></span>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="story-meta">
                                                        <span>Bevétel</span>
                                                        <span>{{ number_format($item->income, 0, ',', ' ') }} Ft</span>
                                                    </div>
                                                    <div class="progress-bar">
                                                        <span class="progress-fill income" style="width: {{ max(6, min(100, round(($item->income / $maxIncome) * 100))) }}%;"></span>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="story-meta">
                                                        <span>Pénzáramlás</span>
                                                        <span>{{ number_format($item->total, 0, ',', ' ') }} Ft</span>
                                                    </div>
                                                    <div class="progress-bar">
                                                        <span class="progress-fill {{ $item->total >= 0 ? '' : 'balance-negative' }}" style="width: {{ max(6, min(100, round((abs($item->total) / $maxBalance) * 100))) }}%;"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state centered">
                                    <h3 class="section-title">Nincs megjeleníthető trend</h3>
                                    <p class="muted-text">Ehhez a nézethez még nem áll rendelkezésre elegendő adat.</p>
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="window">
                        <div class="window-header">
                            @include('partials.window_controls')
                            <div class="window-title-group">
                                <span class="window-title">Kategória bontás</span>
                                <span class="window-subtitle">Hol jelenik meg a legtöbb kiadás</span>
                            </div>
                        </div>

                        <div class="window-body">
                            @if($byCategory && $byCategory->count() > 0)
                                <div class="ranking-list">
                                    @foreach($byCategory as $index => $item)
                                        <article class="ranking-item">
                                            <div class="ranking-head">
                                                <div>
                                                    <span class="ranking-index">#{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                                    <h3 class="transaction-title" style="margin-top: 8px;">{{ $item->name }}</h3>
                                                </div>
                                                <span class="ranking-share">{{ rtrim(rtrim(number_format($item->percent, 2, '.', ''), '0'), '.') }}%</span>
                                            </div>

                                            <div class="progress-bar">
                                                <span class="progress-fill expense" style="width: {{ max(8, min(100, round($item->percent))) }}%;"></span>
                                            </div>

                                            <div class="detail-row">
                                                <span>Kiadás</span>
                                                <strong>{{ number_format($item->total, 0, ',', ' ') }} Ft</strong>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state centered">
                                    <h3 class="section-title">Még nincs kategória adat</h3>
                                    <p class="muted-text">A kiadások megjelennek itt, amint lesz releváns tranzakció az időszakban.</p>
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="window">
                        <div class="window-header">
                            @include('partials.window_controls')
                            <div class="window-title-group">
                                <span class="window-title">Pénznemek</span>
                                <span class="window-subtitle">Összesítés pénznemenként</span>
                            </div>
                        </div>

                        <div class="window-body">
                            @if($byCurrency && $byCurrency->count() > 0)
                                <div class="currency-list">
                                    @foreach($byCurrency as $currency)
                                        <article class="currency-card">
                                            <div class="currency-header">
                                                <div>
                                                    <span class="currency-code">{{ $currency->currency }}</span>
                                                    <h3 class="transaction-title" style="margin-top: 8px;">{{ $currency->currency }}</h3>
                                                </div>
                                                <strong class="currency-balance {{ $currency->total >= 0 ? 'tone-income' : 'tone-expense' }}">
                                                    {{ number_format($currency->total, 0, ',', ' ') }} Ft
                                                </strong>
                                            </div>

                                            <div class="detail-list">
                                                <div class="currency-row">
                                                    <span>Kiadás</span>
                                                    <strong class="tone-expense">{{ number_format($currency->expense, 0, ',', ' ') }} Ft</strong>
                                                </div>
                                                <div class="currency-row">
                                                    <span>Bevétel</span>
                                                    <strong class="tone-income">{{ number_format($currency->income, 0, ',', ' ') }} Ft</strong>
                                                </div>
                                                <div class="currency-row">
                                                    <span>Egyenleg</span>
                                                    <strong class="{{ $currency->total >= 0 ? 'tone-income' : 'tone-expense' }}">{{ number_format($currency->total, 0, ',', ' ') }} Ft</strong>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state centered">
                                    <h3 class="section-title">Nincs pénznem szerinti bontás</h3>
                                    <p class="muted-text">A bontás itt jelenik meg, amint a kiválasztott időszakban lesz mozgás.</p>
                                </div>
                            @endif
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
@endsection
