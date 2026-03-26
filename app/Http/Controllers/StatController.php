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

        $allTranzakciok = Tranzakcio::with('penznem', 'kategoria')
            ->where('felhasznaloid', $userId)
            ->orderByDesc('rogzites')
            ->get();

        $allConverted = $exchangeRateService->convertAllToHUF($allTranzakciok);

        $availableMonths = $allConverted
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
        $selectedMonth = $request->query('honap');

        if (!preg_match('/^\d{4}-\d{2}$/', (string) $selectedMonth) || !$availableMonths->contains($selectedMonth)) {
            $selectedMonth = $availableMonths->contains($currentMonth)
                ? $currentMonth
                : ($availableMonths->first() ?? $currentMonth);
        }

        $tranzakciok = $allConverted
            ->filter(function ($t) use ($selectedMonth) {
                try {
                    return Carbon::parse($t->rogzites)->format('Y-m') === $selectedMonth;
                } catch (\Throwable $e) {
                    return false;
                }
            })
            ->values();

        $expenseTotal = $tranzakciok
            ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
            ->sum('osszeghuf');
        $incomeTotal = $tranzakciok
            ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
            ->sum('osszeghuf');
        $balanceTotal = $incomeTotal - $expenseTotal;
        $total = $expenseTotal;

        $monthly = $allConverted
            ->groupBy(function ($item) {
                try {
                    return Carbon::parse($item->rogzites)->format('Y-m');
                } catch (\Throwable $e) {
                    return null;
                }
            })
            ->filter(function ($items, $month) {
                return !empty($month);
            })
            ->map(function ($items, $month) {
                $income = $items
                    ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
                    ->sum('osszeghuf');
                $expense = $items
                    ->filter(fn($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
                    ->sum('osszeghuf');

                return (object) [
                    'month_key' => $month,
                    'income' => $income,
                    'expense' => $expense,
                    'total' => $income - $expense,
                ];
            })
            ->sortByDesc('month_key')
            ->values();

        $selectedMonthLabel = Carbon::createFromFormat('Y-m', $selectedMonth)
            ->locale('hu')
            ->translatedFormat('Y. F');
        $year = Carbon::createFromFormat('Y-m', $selectedMonth)->year;

        $expenseTransactions = $tranzakciok->where('tipus', 'koltseg')->values();
        $byCategory = $exchangeRateService->getCategoryTotalsInHUF($expenseTransactions);

        $byCategory = $byCategory->map(function ($item) use ($expenseTotal) {
            $item->percent = $expenseTotal ? round($item->total / $expenseTotal * 100, 2) : 0;
            return $item;
        });

        $byCurrencyData = $tranzakciok->groupBy('penznemid');
        $byCurrency = collect();
        foreach ($byCurrencyData as $penznemId => $items) {
            $penznem = $items->first()->penznem;
            $currencyIncome = 0;
            $currencyExpense = 0;

            foreach ($items as $item) {
                $rate = $exchangeRateService->getRate($penznem->nev ?? 'HUF');
                $hufAmount = $item->osszeg * ($rate ?? 1);
                $type = $item->tipus ?? 'koltseg';

                if ($type === 'bevetel') {
                    $currencyIncome += $hufAmount;
                } else {
                    $currencyExpense += $hufAmount;
                }
            }

            $byCurrency->push((object) [
                'currency' => $penznem->nev,
                'income' => $currencyIncome,
                'expense' => $currencyExpense,
                'total' => $currencyIncome - $currencyExpense,
            ]);
        }
        $byCurrency = $byCurrency->sortByDesc('total');

        return view('statisztika', compact(
            'total',
            'monthly',
            'byCategory',
            'byCurrency',
            'year',
            'incomeTotal',
            'expenseTotal',
            'balanceTotal',
            'availableMonths',
            'selectedMonth',
            'selectedMonthLabel'
        ));
    }
}
