<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statisztika - Költség Követő</title>
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
        @import url("https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap");
        :root {
            --ink: #112641;
            --muted: #5a6b82;
            --surface: rgba(255,255,255,0.78);
            --line: rgba(17,38,65,0.14);
            --accent: #1f3b57;
            --accent-soft: #2b4a67;
            --income: #089451;
            --expense: #bf1f3f;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "Sora", "Segoe UI", sans-serif !important;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 10%, rgba(255, 90, 54, 0.25), transparent 40%),
                radial-gradient(circle at 90% 8%, rgba(0, 184, 217, 0.22), transparent 42%),
                linear-gradient(130deg, #fef6e4, #e6f8ff 48%, #f7f8ff) !important;
        }
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.74) !important;
            border-bottom: 1px solid var(--line);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 12px 16px;
        }
        .header h1 {
            font-size: 0.98rem;
            font-weight: 800;
            color: var(--accent);
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.45);
        }
        .header h2 {
            color: var(--muted) !important;
            font-size: 0.82rem;
        }
        .logout-btn {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            border: 1px solid rgba(17, 38, 65, 0.14);
            border-radius: 12px !important;
            background: rgba(255,255,255,0.74) !important;
            color: var(--accent) !important;
            box-shadow: none;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            text-decoration: none;
            transition: transform .18s ease, background-color .18s ease, border-color .18s ease, box-shadow .18s ease;
        }
        .logout-btn:hover {
            transform: translateY(-1px);
            background: rgba(255,255,255,0.92) !important;
            border-color: rgba(17, 38, 65, 0.22);
            box-shadow: 0 8px 18px rgba(17, 38, 65, 0.08);
        }
        .header > div:last-child {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }
        .header > div:last-child .logout-btn {
            margin-right: 0 !important;
        }
        .container {
            width: min(960px, calc(100% - 24px));
            padding: 14px 0 22px !important;
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
        <div>
            <h1>Költség Követő</h1>
            <h2>Statisztika - {{ $selectedView === 'eves' ? 'Éves nézet' : 'Havi nézet' }}</h2>
        </div>
        <div>
            <a href="/fooldal" class="logout-btn" style="margin-right:10px;">Vissza</a>
            <a href="/logout" class="logout-btn">Kijelentkezés</a>
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
                                <td><strong style="color:#b00020;">{{ number_format($c->expense, 0, ',', ' ') }} Ft</strong></td>
                                <td><strong style="color:#1b8f3a;">{{ number_format($c->income, 0, ',', ' ') }} Ft</strong></td>
                                <td><strong style="color: {{ $c->total >= 0 ? '#1b8f3a' : '#b00020' }};">{{ number_format($c->total, 0, ',', ' ') }} Ft</strong></td>
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
