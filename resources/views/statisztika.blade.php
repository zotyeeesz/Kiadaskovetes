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
</head>
<body>
	<div class="header">
		<div>
			<h1>Költség Követő</h1>
			<h2>Statisztika - {{ $year ?? now()->year }}</h2>
		</div>
		<div>
			<a href="/fooldal" class="logout-btn" style="background:#4CAF50; margin-right:10px;">Vissza</a>
			<a href="/logout" class="logout-btn">Kijelentkezés</a>
		</div>
	</div>

	<div class="container">
		<div class="card">
			<div class="summary">
				<div>
					<div class="small-muted">Összes költés</div>
					<div class="big">{{ number_format($total ?? 0, 0, ',', ' ') }} Ft</div>
				</div>
				<div style="margin-left: auto; text-align: right;">
					<div class="small-muted">Időszak</div>
					<div>{{ $year ?? now()->year }}</div>
				</div>
			</div>
		</div>

		<div class="card">
			<h2>Havi bontás</h2>
			@if($monthly && $monthly->count() > 0)
				<table>
					<thead>
						<tr><th>Hónap</th><th>Összeg</th><th></th></tr>
					</thead>
					<tbody>
						@foreach($monthly as $m)
							<tr>
								<td>{{ \Carbon\Carbon::createFromFormat('!m', $m->month)->locale(app()->getLocale())->isoFormat('MMMM') }}</td>
								<td><strong>{{ number_format($m->total, 0, ',', ' ') }} Ft</strong></td>
								<td class="small-muted">{{ number_format(($m->total / max($total,1)) * 100, 2) }} %</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				<div class="small-muted">Nincs adat a kiválasztott időszakban.</div>
			@endif
		</div>

		<div class="card">
			<h2>Kategória szerinti bontás</h2>
			@if($byCategory && $byCategory->count() > 0)
				<table>
					<thead>
						<tr><th>Kategória</th><th>Összeg</th><th>Arány</th></tr>
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
			<h2>Pénznemek szerinti összeg</h2>
			@if($byCurrency && $byCurrency->count() > 0)
				<table>
					<thead><tr><th>Pénznem</th><th>Összeg</th></tr></thead>
					<tbody>
						@foreach($byCurrency as $c)
							<tr>
								<td>{{ $c->currency }}</td>
								<td><strong>{{ number_format($c->total, 0, ',', ' ') }}</strong></td>
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
