<?php

namespace App\Http\Controllers;

use App\Models\Tranzakcio;
use App\Services\ExchangeRateService;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $availableYears = $availableMonths
            ->map(fn ($month) => substr((string) $month, 0, 4))
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $selectedView = $request->query('nezet');
        if (!in_array($selectedView, ['havi', 'eves'], true)) {
            $selectedView = 'havi';
        }

        $currentMonth = Carbon::now()->format('Y-m');
        $selectedMonth = $request->query('honap');

        if (!preg_match('/^\d{4}-\d{2}$/', (string) $selectedMonth) || !$availableMonths->contains($selectedMonth)) {
            $selectedMonth = $availableMonths->contains($currentMonth)
                ? $currentMonth
                : ($availableMonths->first() ?? $currentMonth);
        }

        $selectedYear = $request->query('ev');
        $currentYear = Carbon::now()->format('Y');
        $defaultYear = substr((string) $selectedMonth, 0, 4);

        if (!preg_match('/^\d{4}$/', (string) $selectedYear) || !$availableYears->contains($selectedYear)) {
            if ($availableYears->contains($defaultYear)) {
                $selectedYear = $defaultYear;
            } elseif ($availableYears->contains($currentYear)) {
                $selectedYear = $currentYear;
            } else {
                $selectedYear = $availableYears->first() ?? $currentYear;
            }
        }

        $selectedMonthLabel = Carbon::createFromFormat('Y-m', $selectedMonth)
            ->locale('hu')
            ->translatedFormat('Y. F');
        $selectedYearLabel = $selectedYear . '.';
        $selectedPeriodLabel = $selectedView === 'eves' ? $selectedYearLabel : $selectedMonthLabel;

        $periodTransactions = $allConverted
            ->filter(function ($t) use ($selectedView, $selectedMonth, $selectedYear) {
                try {
                    $date = Carbon::parse($t->rogzites);

                    if ($selectedView === 'eves') {
                        return $date->format('Y') === $selectedYear;
                    }

                    return $date->format('Y-m') === $selectedMonth;
                } catch (\Throwable $e) {
                    return false;
                }
            })
            ->values();

        $expenseTotal = $periodTransactions
            ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
            ->sum('osszeghuf');

        $incomeTotal = $periodTransactions
            ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
            ->sum('osszeghuf');

        $balanceTotal = $incomeTotal - $expenseTotal;

        $trendData = $allConverted
            ->groupBy(function ($item) use ($selectedView) {
                try {
                    $date = Carbon::parse($item->rogzites);
                    return $selectedView === 'eves'
                        ? $date->format('Y')
                        : $date->format('Y-m');
                } catch (\Throwable $e) {
                    return null;
                }
            })
            ->filter(function ($items, $periodKey) {
                return !empty($periodKey);
            })
            ->map(function ($items, $periodKey) use ($selectedView) {
                $income = $items
                    ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
                    ->sum('osszeghuf');

                $expense = $items
                    ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
                    ->sum('osszeghuf');

                $label = $selectedView === 'eves'
                    ? $periodKey . '.'
                    : Carbon::createFromFormat('Y-m', $periodKey)->locale('hu')->translatedFormat('Y. F');

                return (object) [
                    'period_key' => $periodKey,
                    'label' => $label,
                    'income' => $income,
                    'expense' => $expense,
                    'total' => $income - $expense,
                ];
            })
            ->sortByDesc('period_key')
            ->values();

        $expenseTransactions = $periodTransactions
            ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
            ->values();

        $byCategory = $exchangeRateService->getCategoryTotalsInHUF($expenseTransactions)
            ->map(function ($item) use ($expenseTotal) {
                $item->percent = $expenseTotal ? round($item->total / $expenseTotal * 100, 2) : 0;
                return $item;
            });

        $byCurrency = $periodTransactions
            ->groupBy(function ($item) {
                return $item->penznem->nev ?? 'HUF';
            })
            ->map(function ($items, $currency) {
                $nativeIncome = $items
                    ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
                    ->sum('osszeg');

                $nativeExpense = $items
                    ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
                    ->sum('osszeg');

                $income = $items
                    ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'bevetel'))
                    ->sum('osszeghuf');

                $expense = $items
                    ->filter(fn ($t) => (($t->tipus ?? 'koltseg') === 'koltseg'))
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

        $year = (int) $selectedYear;
        $trendTitle = $selectedView === 'eves' ? 'Éves bontás' : 'Havi bontás';
        $trendFirstColumnLabel = $selectedView === 'eves' ? 'Év' : 'Hónap';

        return view('statisztika', compact(
            'trendData',
            'trendTitle',
            'trendFirstColumnLabel',
            'byCategory',
            'byCurrency',
            'year',
            'incomeTotal',
            'expenseTotal',
            'balanceTotal',
            'availableMonths',
            'availableYears',
            'selectedMonth',
            'selectedMonthLabel',
            'selectedYear',
            'selectedYearLabel',
            'selectedView',
            'selectedPeriodLabel'
        ));
    }
}
