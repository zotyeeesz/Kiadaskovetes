<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesSessionUser;
use App\Models\kategoria;
use App\Models\Tranzakcio;
use App\Services\CategoryService;
use App\Services\TransactionInputService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResolvesSessionUser;

    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly TransactionInputService $transactionInputService,
    ) {
    }

    public function store(Request $request): JsonResponse
    {
        $categoryName = trim((string) $request->input('kategoria_nev'));
        $type = $this->transactionInputService->resolveTransactionType($request->input('tipus'));

        if ($categoryName === '') {
            return response()->json([
                'success' => false,
                'message' => 'Adj meg egy kategórianevet.',
            ], 422);
        }

        if (!$type) {
            return response()->json([
                'success' => false,
                'message' => 'Érvényes tranzakciótípus szükséges.',
            ], 422);
        }

        $user = $this->sessionUser($request);
        $category = $this->categoryService->findOrCreate($categoryName, $user->id, $type);

        return response()->json([
            'success' => true,
            'kategoriaid' => $category->id,
            'kategoria_nev' => $category->nev,
            'tipus' => $type,
            'owned' => (int) $category->felhasznaloid === (int) $user->id,
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $category = kategoria::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'A kategória nem található.',
            ], 404);
        }

        $user = $this->sessionUser($request);

        if ((int) $category->felhasznaloid !== (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Csak a saját kategóriádat törölheted.',
            ], 403);
        }

        if (Tranzakcio::where('kategoriaid', $category->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'A kategória használatban van, ezért nem törölhető.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'kategoriaid' => $id,
        ]);
    }
}
