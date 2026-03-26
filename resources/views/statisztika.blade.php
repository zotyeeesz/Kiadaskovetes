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
		}
		.header h1 {
			font-weight: 800;
			color: var(--accent);
			text-shadow: 0 1px 0 rgba(255, 255, 255, 0.45);
		}
		.header h2 { color: var(--muted) !important; }
		.logout-btn {
			border-radius: 12px !important;
			background: var(--accent) !important;
			box-shadow: 0 10px 18px rgba(17, 38, 65, 0.16);
			font-weight: 700;
			transition: transform .2s ease, background-color .2s ease, box-shadow .2s ease;
		}
		.logout-btn:hover {
			transform: translateY(-2px);
			background: var(--accent-soft) !important;
			box-shadow: 0 12px 22px rgba(17, 38, 65, 0.2);
		}
		.container { padding: 24px !important; }
		.card {
			background: var(--surface) !important;
			border: 1px solid var(--line);
			border-radius: 18px !important;
			box-shadow: 0 20px 44px rgba(17, 38, 65, 0.12) !important;
			backdrop-filter: blur(8px);
		}
		h2 { color: var(--ink) !important; }
		.section-title {
			margin: 0;
			font-size: 1rem;
			font-weight: 700;
			letter-spacing: 0.04em;
			color: var(--ink);
		}
		.section-heading {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 16px;
			padding-bottom: 10px;
			border-bottom: 1px solid rgba(17, 38, 65, 0.1);
		}
		.section-heading::after {
			content: "";
			flex: 1;
			height: 1px;
			background: linear-gradient(90deg, rgba(17, 38, 65, 0.18), rgba(17, 38, 65, 0));
		}
		.summary {
			display: grid !important;
			grid-template-columns: repeat(4, minmax(0,1fr));
			gap: 14px !important;
		}
		.summary > div {
			background: rgba(255,255,255,0.86);
			border: 1px solid rgba(17,38,65,0.1);
			border-radius: 12px;
			padding: 10px 12px;
		}
		.summary .big {
			font-size: 22px !important;
			font-family: "JetBrains Mono", monospace;
			font-variant-numeric: tabular-nums;
		}
		table {
			border: 1px solid rgba(17,38,65,0.1);
			border-radius: 12px;
			overflow: hidden;
		}
		table th {
			background: rgba(17,38,65,0.06) !important;
			color: rgba(17, 38, 65, 0.82) !important;
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: .12em;
		}
		table td { border-bottom: 1px solid rgba(17,38,65,0.08) !important; }
		table tr:nth-child(even) td { background: rgba(255,255,255,0.6); }
		table tr:hover td { background: rgba(0,184,217,0.08); }
		.bar { background: rgba(17,38,65,0.12) !important; border-radius: 999px !important; }
		.bar-inner { background: var(--accent) !important; }
		.small-muted { color: var(--muted) !important; }
		.month-switch {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 10px;
		}
		.month-switch .label { font-weight: 700; }
		.month-controls {
			display: flex;
			align-items: center;
			gap: 8px;
			padding: 8px 10px;
			border: 1px solid var(--line);
			border-radius: 12px;
			background: rgba(255,255,255,0.8);
		}
		.month-btn {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			width: 32px;
			height: 32px;
			border-radius: 8px;
			border: 1px solid rgba(17,38,65,0.16);
			text-decoration: none;
			color: var(--ink);
			background: #fff;
			font-weight: 700;
		}
		.month-btn.disabled {
			opacity: .35;
			pointer-events: none;
		}
		.month-current {
			min-width: 150px;
			text-align: center;
			font-weight: 700;
		}
		@media (max-width: 900px) {
			.summary { grid-template-columns: repeat(2, minmax(0,1fr)); }
		}
		@media (max-width: 640px) {
			.header { flex-direction: column; align-items: flex-start; }
			.container { padding: 12px !important; }
			.summary { grid-template-columns: 1fr; }
			table { display: block; overflow-x: auto; white-space: nowrap; }
		}
	</style>
</head>
<body>
	<div class="header">
		<div>
			<h1>Költség Követő</h1>
			<h2>Statisztika - {{ $year ?? now()->year }}</h2>
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
		@endphp

		<div class="card">
			<div class="month-switch">
				<div class="label">Havi n?zet</div>
				<div class="month-controls">
					<a class="month-btn {{ $prevMonth ? '' : 'disabled' }}" href="{{ $prevMonth ? '/statisztika?honap=' . $prevMonth : '#' }}" aria-label="El?z? h?nap">&lsaquo;</a>
					<div class="month-current">{{ $selectedMonthLabel ?? (now()->locale('hu')->translatedFormat('Y. F')) }}</div>
					<a class="month-btn {{ $nextMonth ? '' : 'disabled' }}" href="{{ $nextMonth ? '/statisztika?honap=' . $nextMonth : '#' }}" aria-label="K?vetkez? h?nap">&rsaquo;</a>
				</div>
			</div>
		</div>

		<div class="card">
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
					<div class="small-muted">Egyenleg</div>
					<div class="big" style="color: {{ ($balanceTotal ?? 0) >= 0 ? '#1b8f3a' : '#b00020' }};">
						{{ number_format($balanceTotal ?? 0, 0, ',', ' ') }} Ft
					</div>
				</div>
				<div style="margin-left: auto; text-align: right;">
					<div class="small-muted">Időszak</div>
					<div>{{ $selectedMonthLabel ?? ($year ?? now()->year) }}</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="section-heading">
				<h2 class="section-title">Havi bontás</h2>
			</div>
			@if($monthly && $monthly->count() > 0)
				<table>
					<thead>
							<tr><th>Hónap</th><th>Kiadás</th><th>Bevétel</th><th>Egyenleg</th></tr>
					</thead>
					<tbody>
						@foreach($monthly as $m)
							<tr>
								<td>{{ \Carbon\Carbon::createFromFormat('Y-m', $m->month_key)->locale('hu')->translatedFormat('Y. F') }}</td>
								<td><strong style="color:#b00020;">{{ number_format($m->expense, 0, ',', ' ') }} Ft</strong></td>
								<td><strong style="color:#1b8f3a;">{{ number_format($m->income, 0, ',', ' ') }} Ft</strong></td>
								<td><strong style="color: {{ $m->total >= 0 ? '#1b8f3a' : '#b00020' }};">{{ number_format($m->total, 0, ',', ' ') }} Ft</strong></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<div class="small-muted">Nincs adat a kiválasztott időszakban.</div>
			@endif
		</div>

		<div class="card">
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
				<div class="small-muted">Nincsenek kategória szerinti adatok.</div>
			@endif
		</div>

		<div class="card">
			<div class="section-heading">
				<h2 class="section-title">Pénznemek szerinti bontás</h2>
			</div>
			@if($byCurrency && $byCurrency->count() > 0)
				<table>
					<thead><tr><th>Pénznem</th><th>Kiadás</th><th>Bev?tel</th><th>Egyenleg</th></tr></thead>
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
				<div class="small-muted">Nincsenek pénznem szerinti adatok.</div>
			@endif
		</div>
	</div>
</body>
</html>
