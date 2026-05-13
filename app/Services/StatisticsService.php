<?php

namespace App\Services;

use App\Models\felhasznalo;
use App\Models\Tranzakcio;
use Carbon\Carbon;

class StatisticsService
{
    public function __construct(
        private readonly ExchangeRateService $exchangeRateService,
    ) {
    }

    public function buildViewData(felhasznalo $user, array $query): array
    {
        $allTransactions = Tranzakcio::with('penznem', 'kategoria')
            ->where('felhasznaloid', $user->id)
            ->orderByDesc('rogzites')
            ->get();

        $allConverted = $this->exchangeRateService->convertAllToHUF($allTransactions);

        $availableMonths = $allConverted
            ->map(function ($transaction) {
                try {
                    return Carbon::parse($transaction->rogzites)->format('Y-m');
                } catch (\Throwable) {
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

        $selectedView = in_array(($query['nezet'] ?? null), ['havi', 'eves'], true)
            ? $query['nezet']
            : 'havi';

        $currentMonth = Carbon::now()->format('Y-m');
        $selectedMonth = (string) ($query['honap'] ?? '');

        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth) || !$availableMonths->contains($selectedMonth)) {
            $selectedMonth = $availableMonths->contains($currentMonth)
                ? $currentMonth
                : ($availableMonths->first() ?? $currentMonth);
        }

        $selectedYear = (string) ($query['ev'] ?? '');
        $currentYear = Carbon::now()->format('Y');
        $defaultYear = substr($selectedMonth, 0, 4);

        if (!preg_match('/^\d{4}$/', $selectedYear) || !$availableYears->contains($selectedYear)) {
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
            ->filter(function ($transaction) use ($selectedView, $selectedMonth, $selectedYear) {
                try {
                    $date = Carbon::parse($transaction->rogzites);

                    if ($selectedView === 'eves') {
                        return $date->format('Y') === $selectedYear;
                    }

                    return $date->format('Y-m') === $selectedMonth;
                } catch (\Throwable) {
                    return false;
                }
            })
            ->values();

        $expenseTotal = $periodTransactions
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'koltseg'))
            ->sum('osszeghuf');
        $incomeTotal = $periodTransactions
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'bevetel'))
            ->sum('osszeghuf');
        $balanceTotal = $incomeTotal - $expenseTotal;

        $trendData = $allConverted
            ->groupBy(function ($item) use ($selectedView) {
                try {
                    $date = Carbon::parse($item->rogzites);

                    return $selectedView === 'eves'
                        ? $date->format('Y')
                        : $date->format('Y-m');
                } catch (\Throwable) {
                    return null;
                }
            })
            ->filter(fn ($items, $periodKey) => !empty($periodKey))
            ->map(function ($items, $periodKey) use ($selectedView) {
                $income = $items
                    ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'bevetel'))
                    ->sum('osszeghuf');

                $expense = $items
                    ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'koltseg'))
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
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'koltseg'))
            ->values();

        $byCategory = $this->exchangeRateService->getCategoryTotalsInHUF($expenseTransactions)
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
                    ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'bevetel'))
                    ->sum('osszeg');

                $nativeExpense = $items
                    ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'koltseg'))
                    ->sum('osszeg');

                $income = $items
                    ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'bevetel'))
                    ->sum('osszeghuf');

                $expense = $items
                    ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'koltseg'))
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

        return [
            'trendData' => $trendData,
            'trendTitle' => $selectedView === 'eves' ? 'Éves bontás' : 'Havi bontás',
            'trendFirstColumnLabel' => $selectedView === 'eves' ? 'Év' : 'Hónap',
            'byCategory' => $byCategory,
            'byCurrency' => $byCurrency,
            'year' => (int) $selectedYear,
            'incomeTotal' => $incomeTotal,
            'expenseTotal' => $expenseTotal,
            'balanceTotal' => $balanceTotal,
            'availableMonths' => $availableMonths,
            'availableYears' => $availableYears,
            'selectedMonth' => $selectedMonth,
            'selectedMonthLabel' => $selectedMonthLabel,
            'selectedYear' => $selectedYear,
            'selectedYearLabel' => $selectedYearLabel,
            'selectedView' => $selectedView,
            'selectedPeriodLabel' => $selectedPeriodLabel,
        ];
    }
}
