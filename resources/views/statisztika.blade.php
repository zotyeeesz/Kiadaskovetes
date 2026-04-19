<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statisztika - SpendWise</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f5f5f5; }
        .header { background-color: #667eea; color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; }
        .logout-btn { background-color: #d32f2f; color: white; border: none; padding: 8px 14px; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .container { padding: 30px; max-width: 1100px; margin: 0 auto; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
        h2 { color: #333; margin: 0 0 12px 0; }
        .summary { display: flex; gap: 20px; align-items: center; }
        .summary .big { font-size: 28px; font-weight: bold; color: #222; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        table th { background-color: #f0f4ff; color: #333; }
        .bar { height: 14px; background: #e9eefb; border-radius: 8px; overflow: hidden; }
        .bar-inner { height: 100%; background: linear-gradient(90deg,#667eea,#5568d3); }
        .small-muted { color: #666; font-size: 13px; }
    </style>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap");
        :root {
            --ink: #1e293b;
            --muted: #64748b;
            --surface: #ffffff;
            --line: rgba(30, 41, 59, 0.1);
            --accent: #059669;
            --accent-soft: #10b981;
            --accent-light: rgba(5, 150, 105, 0.1);
            --income: #10b981;
            --expense: #ef4444;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "Instrument Sans", "Segoe UI", sans-serif !important;
            color: var(--ink);
            background: #f8fafc !important;
        }
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #ffffff !important;
            border-bottom: 1px solid var(--line);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 12px 24px;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .header-logo {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 12px;
            color: #111827;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        }
        .header-logo svg {
            width: 100%;
            height: 100%;
            overflow: visible;
        }
        .header-title {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .header-title h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.5px;
        }
        .header-subtitle {
            font-size: 0.75rem;
            color: var(--muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .back-btn {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 10px !important;
            background: #ffffff !important;
            color: var(--ink) !important;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s ease;
        }
        .back-btn:hover {
            background: var(--accent-light) !important;
            border-color: var(--accent);
            color: var(--accent) !important;
        }
        .logout-btn {
            min-height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 10px !important;
            background: #ffffff !important;
            color: var(--ink) !important;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s ease;
        }
        .logout-btn:hover {
            transform: translateY(-1px);
            background: var(--accent-light) !important;
            border-color: var(--accent);
            color: var(--accent) !important;
        }
        .container {
            width: min(960px, calc(100% - 24px));
            padding: 16px 0 22px !important;
            margin: 0 auto;
        }
        .card {
            background: var(--surface) !important;
            border: 1px solid var(--line);
            border-radius: 16px !important;
            box-shadow: 0 12px 24px rgba(17, 38, 65, 0.08) !important;
            backdrop-filter: blur(8px);
            padding: 14px !important;
            margin-bottom: 14px !important;
        }
        h2 { color: var(--ink) !important; }
        .section-title {
            margin: 0;
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: var(--ink);
        }
        .section-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            padding-bottom: 7px;
            border-bottom: 1px solid rgba(17, 38, 65, 0.1);
        }
        .section-heading::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(17, 38, 65, 0.18), rgba(17, 38, 65, 0));
        }
        .summary-layout { display: block; }
        .summary {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0,1fr)) minmax(210px, 0.88fr);
            gap: 8px !important;
            align-content: start;
        }
        .summary > div {
            background: rgba(255,255,255,0.86);
            border: 1px solid rgba(17,38,65,0.1);
            border-radius: 12px;
            padding: 9px 10px;
            min-height: 62px;
            display: grid;
            align-content: space-between;
        }
        .summary .big {
            font-size: clamp(0.96rem, 1.35vw, 1.18rem) !important;
            font-family: "JetBrains Mono", monospace;
            font-variant-numeric: tabular-nums;
            line-height: 1.08;
        }
        .summary .small-muted {
            font-size: 10px;
            letter-spacing: .02em;
        }
        .summary-panel {
            display: grid;
            gap: 8px;
            padding: 9px 10px;
            border: 1px solid rgba(17,38,65,0.1);
            border-radius: 12px;
            background: rgba(255,255,255,0.82);
            min-height: 100%;
            align-self: stretch;
        }
        .summary-selector {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 12px;
        }
        .summary-selector-head {
            display: grid;
            gap: 10px;
        }
        .summary-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
        }
        table {
            border: 1px solid rgba(17,38,65,0.1);
            border-radius: 14px;
            overflow: hidden;
        }
        table th, table td {
            padding: 7px 9px;
        }
        table th {
            background: rgba(17,38,65,0.06) !important;
            color: rgba(17, 38, 65, 0.82) !important;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        table td {
            font-size: 12px;
            border-bottom: 1px solid rgba(17,38,65,0.08) !important;
        }
        table tr:nth-child(even) td { background: rgba(255,255,255,0.6); }
        table tr:hover td { background: rgba(0,184,217,0.08); }
        .bar {
            height: 10px;
            background: rgba(17,38,65,0.12) !important;
            border-radius: 999px !important;
        }
        .bar-inner { background: var(--accent) !important; }
        .small-muted {
            color: var(--muted) !important;
            font-size: 10px;
        }
        .switch-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .month-switch .label { font-weight: 700; }
        .view-switch {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 4px;
            padding: 4px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: rgba(255,255,255,0.7);
        }
        .view-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 66px;
            padding: 7px 10px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--ink);
            font-size: 11px;
            font-weight: 700;
            border: 1px solid transparent;
        }
        .view-toggle.active {
            background: var(--accent);
            color: #fff;
            box-shadow: 0 7px 12px rgba(17, 38, 65, 0.13);
        }
        .month-controls {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px 7px;
            border: 1px solid var(--line);
            border-radius: 9px;
            background: rgba(255,255,255,0.8);
        }
        .month-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1px solid rgba(17,38,65,0.16);
            text-decoration: none;
            color: var(--ink);
            background: #fff;
            font-size: 11px;
            font-weight: 700;
        }
        .month-btn.disabled {
            opacity: .35;
            pointer-events: none;
        }
        .month-current {
            min-width: 112px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
        }
        .data-card {
            display: grid;
            gap: 8px;
        }
        @media (max-width: 900px) {
            .summary {
                grid-template-columns: repeat(2, minmax(0,1fr));
            }
        }
        @media (max-width: 640px) {
            .header { flex-direction: column; align-items: flex-start; }
            .container {
                width: min(100%, calc(100% - 20px));
                padding: 10px 0 18px !important;
            }
            .card {
                padding: 12px !important;
            }
            .summary { grid-template-columns: 1fr; }
            .month-controls, .view-switch { width: 100%; }
            .view-switch { justify-content: stretch; }
            .view-toggle { flex: 1; }
            .month-current { min-width: 0; flex: 1; }
            table { display: block; overflow-x: auto; white-space: nowrap; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-brand">
            <div class="header-logo">
                @include('partials.app_logo')
            </div>
            <div class="header-title">
                <h1>SpendWise</h1>
                <span class="header-subtitle">Statisztika - {{ $selectedView === 'eves' ? 'Éves' : 'Havi' }} nézet</span>
            </div>
        </div>
        <div class="header-user">
            <a href="/fooldal" class="back-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Vissza
            </a>
            <a href="/logout" class="logout-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Kijelentkezés
            </a>
        </div>
    </div>

    <div class="container">
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
        @endphp

        <div class="card">
            <div class="summary-layout">
            <div class="summary">
                <div>
                    <div class="small-muted">Összes kiadás</div>
                    <div class="big" style="color:#b00020;">{{ number_format($expenseTotal ?? 0, 0, ',', ' ') }} Ft</div>
                </div>
                <div>
                    <div class="small-muted">Összes bevétel</div>
                    <div class="big" style="color:#1b8f3a;">{{ number_format($incomeTotal ?? 0, 0, ',', ' ') }} Ft</div>
                </div>
                <div>
                    <div class="small-muted">Pénzáramlás</div>
                    <div class="big" style="color: {{ ($balanceTotal ?? 0) >= 0 ? '#1b8f3a' : '#b00020' }};">
                        {{ number_format($balanceTotal ?? 0, 0, ',', ' ') }} Ft
                    </div>
                </div>
                <div class="summary-panel summary-selector">
                    <div class="summary-selector-head">
                        <div class="summary-label">Statisztikai nézet</div>
                        <div class="view-switch">
                            <a class="view-toggle {{ $selectedView === 'havi' ? 'active' : '' }}" href="/statisztika?nezet=havi&honap={{ $selectedMonth }}">
                                Havi
                            </a>
                            <a class="view-toggle {{ $selectedView === 'eves' ? 'active' : '' }}" href="/statisztika?nezet=eves&ev={{ $selectedYear }}">
                                Éves
                            </a>
                        </div>
                    </div>

                    <div class="month-controls">
                        @if($selectedView === 'eves')
                            <a class="month-btn {{ $prevYear ? '' : 'disabled' }}" href="{{ $prevYear ? '/statisztika?nezet=eves&ev=' . $prevYear : '#' }}" aria-label="Előző év">&lsaquo;</a>
                            <div class="month-current">{{ $selectedYearLabel }}</div>
                            <a class="month-btn {{ $nextYear ? '' : 'disabled' }}" href="{{ $nextYear ? '/statisztika?nezet=eves&ev=' . $nextYear : '#' }}" aria-label="Következő év">&rsaquo;</a>
                        @else
                            <a class="month-btn {{ $prevMonth ? '' : 'disabled' }}" href="{{ $prevMonth ? '/statisztika?nezet=havi&honap=' . $prevMonth : '#' }}" aria-label="Előző hónap">&lsaquo;</a>
                            <div class="month-current">{{ $selectedMonthLabel }}</div>
                            <a class="month-btn {{ $nextMonth ? '' : 'disabled' }}" href="{{ $nextMonth ? '/statisztika?nezet=havi&honap=' . $nextMonth : '#' }}" aria-label="Következő hónap">&rsaquo;</a>
                        @endif
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="card data-card">
            <div class="section-heading">
                <h2 class="section-title">{{ $trendTitle }}</h2>
            </div>
            @if($trendData && $trendData->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>{{ $trendFirstColumnLabel }}</th>
                            <th>Kiadás</th>
                            <th>Bevétel</th>
                            <th>Pénzáramlás</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trendData as $item)
                            <tr>
                                <td>{{ $item->label }}</td>
                                <td><strong style="color:#b00020;">{{ number_format($item->expense, 0, ',', ' ') }} Ft</strong></td>
                                <td><strong style="color:#1b8f3a;">{{ number_format($item->income, 0, ',', ' ') }} Ft</strong></td>
                                <td><strong style="color: {{ $item->total >= 0 ? '#1b8f3a' : '#b00020' }};">{{ number_format($item->total, 0, ',', ' ') }} Ft</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="small-muted">Nincs adat a kiválasztott nézethez.</div>
            @endif
        </div>

        <div class="card data-card">
            <div class="section-heading">
                <h2 class="section-title">Kategória szerinti kiadás bontás</h2>
            </div>
            @if($byCategory && $byCategory->count() > 0)
                <table>
                    <thead>
                        <tr><th>Kategória</th><th>Kiadás</th><th>Arány</th></tr>
                    </thead>
                    <tbody>
                        @foreach($byCategory as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td><strong>{{ number_format($item->total, 0, ',', ' ') }} Ft</strong></td>
                                <td style="width:40%">
                                    <div class="bar" title="{{ $item->percent }}%">
                                        <div class="bar-inner" style="width: {{ $item->percent }}%;"></div>
                                    </div>
                                    <div class="small-muted">{{ $item->percent }} %</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="small-muted">Nincsenek kategória szerinti adatok a kiválasztott időszakban.</div>
            @endif
        </div>

        <div class="card data-card">
            <div class="section-heading">
                <h2 class="section-title">Pénznemek szerinti bontás</h2>
            </div>
            @if($byCurrency && $byCurrency->count() > 0)
                <table>
                    <thead><tr><th>Pénznem</th><th>Kiadás</th><th>Bevétel</th><th>Pénzáramlás</th></tr></thead>
                    <tbody>
                        @foreach($byCurrency as $c)
                            <tr>
                                <td>{{ $c->currency }}</td>
                                <td>
                                    <strong style="color:#b00020;">{{ number_format($c->native_expense, 2, ',', ' ') }} {{ $c->currency }}</strong>
                                    @if($c->currency !== 'HUF')
                                        <div class="small-muted">{{ number_format($c->expense, 0, ',', ' ') }} Ft</div>
                                    @endif
                                </td>
                                <td>
                                    <strong style="color:#1b8f3a;">{{ number_format($c->native_income, 2, ',', ' ') }} {{ $c->currency }}</strong>
                                    @if($c->currency !== 'HUF')
                                        <div class="small-muted">{{ number_format($c->income, 0, ',', ' ') }} Ft</div>
                                    @endif
                                </td>
                                <td>
                                    <strong style="color: {{ $c->native_total >= 0 ? '#1b8f3a' : '#b00020' }};">{{ number_format($c->native_total, 2, ',', ' ') }} {{ $c->currency }}</strong>
                                    @if($c->currency !== 'HUF')
                                        <div class="small-muted">{{ number_format($c->total, 0, ',', ' ') }} Ft</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="small-muted">Nincsenek pénznem szerinti adatok a kiválasztott időszakban.</div>
            @endif
        </div>
    </div>
</body>
</html>
