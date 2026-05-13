<?php

namespace App\Services;

use App\Models\kategoria;
use App\Models\Tranzakcio;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(
        private readonly TransactionInputService $transactionInputService,
    ) {
    }

    public function getSuggestedCategoriesByType(int $userId, string $type): Collection
    {
        $normalizedType = $this->transactionInputService->resolveTransactionType($type) ?? 'koltseg';
        $usedCategoryIds = Tranzakcio::where('felhasznaloid', $userId)
            ->where('tipus', $normalizedType)
            ->pluck('kategoriaid')
            ->filter()
            ->unique()
            ->values();

        $databaseCategories = kategoria::query()
            ->where(function ($query) use ($userId) {
                $query->where('felhasznaloid', $userId)->orWhereNull('felhasznaloid');
            })
            ->where(function ($query) use ($normalizedType, $usedCategoryIds) {
                $query->where('tipus', $normalizedType)
                    ->orWhereNull('tipus');

                if ($usedCategoryIds->isNotEmpty()) {
                    $query->orWhereIn('id', $usedCategoryIds);
                }
            })
            ->get();

        $defaultCategories = collect($this->defaultCategoryNamesByType($normalizedType))
            ->map(function ($name) {
                return new kategoria([
                    'felhasznaloid' => null,
                    'nev' => $name,
                ]);
            });

        return $defaultCategories
            ->concat($databaseCategories)
            ->groupBy(fn (kategoria $category) => mb_strtolower(trim($category->nev)))
            ->map(function (Collection $group) {
                return $group->sortByDesc(function (kategoria $category) {
                    return $category->felhasznaloid ? 1 : 0;
                })->first();
            })
            ->sortBy('nev', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }

    public function findOrCreate(string $categoryName, int $userId, ?string $type = null): kategoria
    {
        $trimmedName = trim($categoryName);
        $normalizedType = $this->transactionInputService->resolveTransactionType($type);

        $existingCategory = kategoria::whereRaw('LOWER(TRIM(nev)) = LOWER(?)', [$trimmedName])
            ->where(function ($query) use ($userId) {
                $query->where('felhasznaloid', $userId)->orWhereNull('felhasznaloid');
            })
            ->where(function ($query) use ($normalizedType) {
                $query->where('tipus', $normalizedType)->orWhereNull('tipus');
            })
            ->orderByRaw('CASE WHEN felhasznaloid IS NULL THEN 1 ELSE 0 END')
            ->orderByRaw('CASE WHEN tipus = ? THEN 0 WHEN tipus IS NULL THEN 1 ELSE 2 END', [$normalizedType])
            ->first();

        if ($existingCategory) {
            return $existingCategory;
        }

        return kategoria::create([
            'felhasznaloid' => $userId,
            'nev' => $trimmedName,
            'tipus' => $normalizedType,
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function defaultCategoryNamesByType(string $type): array
    {
        return $type === 'bevetel'
            ? [
                'fizetés',
                'bónusz',
                'ajándék',
                'eladás',
                'kamat',
                'ösztöndíj',
            ]
            : [
                'bevásárlás',
                'szórakozás',
                'vendéglátás',
                'egészség',
                'ruházat',
                'közlekedés',
                'befektetés',
            ];
    }
}
