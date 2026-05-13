<?php

namespace App\Services;

use App\Models\arbazis;
use App\Models\felhasznalo;
use App\Models\penznem;
use App\Models\Tranzakcio;
use Carbon\Carbon;

class DashboardService
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly CurrencyService $currencyService,
        private readonly ExchangeRateService $exchangeRateService,
        private readonly TransactionInputService $transactionInputService,
    ) {
    }

    public function buildViewData(felhasznalo $user, array $query): array
    {
        $this->currencyService->ensureDefaultCurrenciesExist();

        $transactions = Tranzakcio::with('kategoria', 'penznem')
            ->where('felhasznaloid', $user->id)
            ->orderByDesc('rogzites')
            ->get();

        $rates = collect();

        try {
            $rates = arbazis::with('penznem')->get()->keyBy('penznemid');
        } catch (\Throwable) {
            $rates = collect();
        }

        $convertedTransactions = $this->exchangeRateService->convertAllToHUF($transactions);
        $availableMonths = $convertedTransactions
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

        $currentMonth = Carbon::now()->format('Y-m');
        $selectedMonth = (string) ($query['honap'] ?? '');

        if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth) || !$availableMonths->contains($selectedMonth)) {
            $selectedMonth = $availableMonths->contains($currentMonth)
                ? $currentMonth
                : ($availableMonths->first() ?? $currentMonth);
        }

        $selectedMonthTransactions = $convertedTransactions
            ->filter(function ($transaction) use ($selectedMonth) {
                try {
                    return Carbon::parse($transaction->rogzites)->format('Y-m') === $selectedMonth;
                } catch (\Throwable) {
                    return false;
                }
            })
            ->sortByDesc('rogzites')
            ->values();

        $selectedMonthLabel = Carbon::createFromFormat('Y-m', $selectedMonth)
            ->locale('hu')
            ->translatedFormat('Y. F');

        $filters = [
            'tipus' => $this->transactionInputService->resolveTransactionType($query['szuro_tipus'] ?? null) ?? '',
            'kategoria' => $this->transactionInputService->normalizeSearchText($query['szuro_kategoria'] ?? null),
            'penznem' => $this->currencyService->normalizeCurrencyCode($query['szuro_penznem'] ?? null),
            'osszeg_min' => $this->transactionInputService->normalizeAmountValue($query['szuro_osszeg_min'] ?? null),
            'osszeg_max' => $this->transactionInputService->normalizeAmountValue($query['szuro_osszeg_max'] ?? null),
            'datum_tol' => $this->transactionInputService->normalizeSearchText($query['szuro_datum_tol'] ?? null),
            'datum_ig' => $this->transactionInputService->normalizeSearchText($query['szuro_datum_ig'] ?? null),
            'kereses' => $this->transactionInputService->normalizeSearchText($query['szuro_kereses'] ?? null),
        ];

        $hasDateRangeFilter = preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['datum_tol'])
            || preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['datum_ig']);
        $isDetailedSearchActive = $filters['tipus'] !== ''
            || $filters['kategoria'] !== ''
            || $filters['penznem'] !== ''
            || $filters['osszeg_min'] !== null
            || $filters['osszeg_max'] !== null
            || $filters['kereses'] !== ''
            || $hasDateRangeFilter;

        $baseTransactions = $hasDateRangeFilter ? $convertedTransactions : $selectedMonthTransactions;

        $filteredTransactions = $baseTransactions
            ->filter(function ($transaction) use ($filters, $hasDateRangeFilter) {
                try {
                    $recordedAt = Carbon::parse($transaction->rogzites);
                } catch (\Throwable) {
                    return false;
                }

                if ($hasDateRangeFilter) {
                    if (
                        preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['datum_tol'])
                        && $recordedAt->lt(Carbon::parse($filters['datum_tol'])->startOfDay())
                    ) {
                        return false;
                    }

                    if (
                        preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['datum_ig'])
                        && $recordedAt->gt(Carbon::parse($filters['datum_ig'])->endOfDay())
                    ) {
                        return false;
                    }
                }

                if ($filters['tipus'] !== '' && (($transaction->tipus ?? 'koltseg') !== $filters['tipus'])) {
                    return false;
                }

                $categoryName = mb_strtolower(trim((string) ($transaction->kategoria->nev ?? '')));
                if ($filters['kategoria'] !== '' && !str_contains($categoryName, mb_strtolower($filters['kategoria']))) {
                    return false;
                }

                $currencyCode = $this->currencyService->normalizeCurrencyCode($transaction->penznem->nev ?? 'HUF');
                if ($filters['penznem'] !== '' && $currencyCode !== $filters['penznem']) {
                    return false;
                }

                $amount = (float) ($transaction->osszeg ?? 0);
                if ($filters['osszeg_min'] !== null && $amount < $filters['osszeg_min']) {
                    return false;
                }

                if ($filters['osszeg_max'] !== null && $amount > $filters['osszeg_max']) {
                    return false;
                }

                $search = mb_strtolower($filters['kereses']);
                if ($search !== '') {
                    $haystack = mb_strtolower(implode(' ', [
                        (string) ($transaction->kategoria->nev ?? ''),
                        (string) ($transaction->megjegyzes ?? ''),
                        (string) ($transaction->penznem->nev ?? ''),
                        (string) ($transaction->rogzites ?? ''),
                        (string) ($transaction->tipus ?? 'koltseg'),
                    ]));

                    if (!str_contains($haystack, $search)) {
                        return false;
                    }
                }

                return true;
            })
            ->sortByDesc('rogzites')
            ->values();

        $selectedRangeLabel = '';
        if ($hasDateRangeFilter) {
            $fromLabel = preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['datum_tol'])
                ? Carbon::parse($filters['datum_tol'])->locale('hu')->translatedFormat('Y. m. d.')
                : null;
            $toLabel = preg_match('/^\d{4}-\d{2}-\d{2}$/', $filters['datum_ig'])
                ? Carbon::parse($filters['datum_ig'])->locale('hu')->translatedFormat('Y. m. d.')
                : null;

            $selectedRangeLabel = $fromLabel && $toLabel
                ? $fromLabel . ' - ' . $toLabel
                : ($fromLabel ? $fromLabel . ' után' : ($toLabel ? $toLabel . ' előtt' : 'Egyedi időszak'));
        }

        $listTitle = $hasDateRangeFilter
            ? 'Tranzakcióid - ' . $selectedRangeLabel
            : 'Tranzakcióid - ' . $selectedMonthLabel;

        $monthNavigationQuery = http_build_query(array_filter([
            'szuro_tipus' => $filters['tipus'],
            'szuro_kategoria' => $filters['kategoria'],
            'szuro_penznem' => $filters['penznem'],
            'szuro_osszeg_min' => $query['szuro_osszeg_min'] ?? null,
            'szuro_osszeg_max' => $query['szuro_osszeg_max'] ?? null,
            'szuro_datum_tol' => $filters['datum_tol'],
            'szuro_datum_ig' => $filters['datum_ig'],
            'szuro_kereses' => $filters['kereses'],
        ], fn ($value) => $value !== null && $value !== ''));

        $expenseTotal = $filteredTransactions
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'koltseg'))
            ->sum('osszeghuf');
        $incomeTotal = $filteredTransactions
            ->filter(fn ($transaction) => (($transaction->tipus ?? 'koltseg') === 'bevetel'))
            ->sum('osszeghuf');
        $balanceTotal = $incomeTotal - $expenseTotal;

        $byCurrency = $filteredTransactions
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
            'tranzakciokAtvalasztva' => $filteredTransactions,
            'koltsegKategoriak' => $this->categoryService->getSuggestedCategoriesByType($user->id, 'koltseg'),
            'bevetelKategoriak' => $this->categoryService->getSuggestedCategoriesByType($user->id, 'bevetel'),
            'penznemek' => penznem::orderBy('nev')->get(),
            'expenseTotal' => $expenseTotal,
            'incomeTotal' => $incomeTotal,
            'balanceTotal' => $balanceTotal,
            'byCurrency' => $byCurrency,
            'arfolyamok' => $rates,
            'tranzakciok' => $transactions,
            'availableMonths' => $availableMonths,
            'selectedMonth' => $selectedMonth,
            'selectedMonthLabel' => $selectedMonthLabel,
            'filters' => $filters,
            'isDetailedSearchActive' => $isDetailedSearchActive,
            'hasDateRangeFilter' => $hasDateRangeFilter,
            'listTitle' => $listTitle,
            'monthNavigationQuery' => $monthNavigationQuery,
        ];
    }
}
