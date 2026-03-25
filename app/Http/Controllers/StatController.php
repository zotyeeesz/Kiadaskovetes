<?php

namespace App\Http\Controllers;

use App\Models\Tranzakcio;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
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

        $tranzakciok = Tranzakcio::with('penznem', 'kategoria')->where('felhasznaloid', $userId)->get();
        $expenseTotal = 0;
        $incomeTotal = 0;

        foreach ($tranzakciok as $t) {
            $rate = $exchangeRateService->getRate($t->penznem->nev ?? 'HUF');
            $hufAmount = $t->osszeg * ($rate ?? 1);
            $type = $t->tipus ?? 'koltseg';

            if ($type === 'bevetel') {
                $incomeTotal += $hufAmount;
            } else {
                $expenseTotal += $hufAmount;
            }
        }
        $balanceTotal = $incomeTotal - $expenseTotal;
        $total = $expenseTotal;

        // Havi bontás az aktuális évre (a dátum mező neve: 'rogzites')
        $year = Carbon::now()->year;
        $monthlyData = Tranzakcio::with('penznem')
            ->where('felhasznaloid', $userId)
            ->whereYear('rogzites', $year)
            ->get();

        $monthly = collect();
        foreach ($monthlyData->groupBy(fn($item) => Carbon::parse($item->rogzites)->format('m')) as $month => $items) {
            $monthIncome = 0;
            $monthExpense = 0;

            foreach ($items as $item) {
                $rate = $exchangeRateService->getRate($item->penznem->nev ?? '');
                $hufAmount = $item->osszeg * ($rate ?? 1);
                $type = $item->tipus ?? 'koltseg';

                if ($type === 'bevetel') {
                    $monthIncome += $hufAmount;
                } else {
                    $monthExpense += $hufAmount;
                }
            }

            $monthly->push((object)[
                'month' => $month,
                'income' => $monthIncome,
                'expense' => $monthExpense,
                'total' => $monthIncome - $monthExpense
            ]);
        }
        $monthly = $monthly->sortBy('month');

        // Kategória szerinti kiadás bontás (forintban)
        $expenseTransactions = $tranzakciok->where('tipus', 'koltseg')->values();
        $byCategory = $exchangeRateService->getCategoryTotalsInHUF($expenseTransactions);

        // Százalékok számítása
        $byCategory = $byCategory->map(function ($item) use ($expenseTotal) {
            $item->percent = $expenseTotal ? round($item->total / $expenseTotal * 100, 2) : 0;
            return $item;
        });

        // Összeg pénznemenként (HUF-ban), bevétel/kiadás bontásban
        $byCurrencyData = $tranzakciok->groupBy('penznemid');
        $byCurrency = collect();
        foreach ($byCurrencyData as $penznemId => $items) {
            $penznem = $items->first()->penznem;
            $currencyIncome = 0;
            $currencyExpense = 0;

            foreach ($items as $item) {
                $rate = $exchangeRateService->getRate($penznem->nev);
                $hufAmount = $item->osszeg * ($rate ?? 1);
                $type = $item->tipus ?? 'koltseg';

                if ($type === 'bevetel') {
                    $currencyIncome += $hufAmount;
                } else {
                    $currencyExpense += $hufAmount;
                }
            }
             
            $byCurrency->push((object)[
                'currency' => $penznem->nev,
                'income' => $currencyIncome,
                'expense' => $currencyExpense,
                'total' => $currencyIncome - $currencyExpense
            ]);
        }
        $byCurrency = $byCurrency->sortByDesc('total');

        return view('statisztika', compact('total', 'monthly', 'byCategory', 'byCurrency', 'year', 'incomeTotal', 'expenseTotal', 'balanceTotal'));
    }
}
