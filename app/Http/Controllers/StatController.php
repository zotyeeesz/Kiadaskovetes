<?php

namespace App\Http\Controllers;

use App\Models\Tranzakcio;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatController extends Controller
{
    public function index(Request $request)
    {
        if (!session('user')) {
            return redirect('/login');
        }

        $userId = session('user')->id;
        $exchangeRateService = new ExchangeRateService();

        // Összes költés (HUF-ban)
        $tranzakciok = Tranzakcio::where('felhasznaloid', $userId)->get();
        $total = 0;
        foreach ($tranzakciok as $t) {
            $rate = $exchangeRateService->getRate($t->penznem->nev);
            $total += $t->osszeg * ($rate ?? 1);
        }

        // Havi bontás az aktuális évre (a dátum mező neve: 'rogzites')
        $year = Carbon::now()->year;
        $driver = DB::getDriverName();
        $monthExpr = $driver === 'sqlite' ? "strftime('%m', rogzites)" : 'MONTH(rogzites)';

        // Havi összegzés forintra konvertálva
        $monthlyData = Tranzakcio::selectRaw("{$monthExpr} as month, osszeg, penznemid")
            ->where('felhasznaloid', $userId)
            ->whereYear('rogzites', $year)
            ->get();

        $monthly = collect();
        foreach ($monthlyData->groupBy('month') as $month => $items) {
            $total_huf = 0;
            foreach ($items as $item) {
                $rate = $exchangeRateService->getRate($item->penznem->nev ?? '');
                $total_huf += $item->osszeg * ($rate ?? 1);
            }
            $monthly->push((object)[
                'month' => $month,
                'total' => $total_huf
            ]);
        }
        $monthly = $monthly->sortBy('month');

        // Kategória szerinti összegek (forintban)
        $byCategory = $exchangeRateService->getCategoryTotalsInHUF($tranzakciok);

        // Százalékok számítása
        $byCategory = $byCategory->map(function ($item) use ($total) {
            $item->percent = $total ? round($item->total / $total * 100, 2) : 0;
            return $item;
        });

        // Összeg pénznemenként (HUF-ban)
        $byCurrencyData = $tranzakciok->groupBy('penznemid');
        $byCurrency = collect();
        foreach ($byCurrencyData as $penznemId => $items) {
            $penznem = $items->first()->penznem;
            $total_huf = 0;
            foreach ($items as $item) {
                $rate = $exchangeRateService->getRate($penznem->nev);
                $total_huf += $item->osszeg * ($rate ?? 1);
            }
            
            $byCurrency->push((object)[
                'currency' => $penznem->nev,
                'total' => $total_huf
            ]);
        }
        $byCurrency = $byCurrency->sortByDesc('total');

        return view('statisztika', compact('total', 'monthly', 'byCategory', 'byCurrency', 'year'));
    }
}
