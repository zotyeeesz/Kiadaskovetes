<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesSessionUser;
use App\Models\Tranzakcio;
use App\Services\CategoryService;
use App\Services\CurrencyService;
use App\Services\TransactionInputService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use ResolvesSessionUser;

    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly CurrencyService $currencyService,
        private readonly TransactionInputService $transactionInputService,
    ) {
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->persist($request);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $transaction = Tranzakcio::find($id);

        if (!$transaction) {
            return redirect('/fooldal')->withErrors(['error' => 'A tranzakció nem található!']);
        }

        $user = $this->sessionUser($request);

        if ((int) $transaction->felhasznaloid !== (int) $user->id) {
            return redirect('/fooldal')->withErrors(['error' => 'Nincs jogod ezt a tranzakciót szerkeszteni!']);
        }

        return $this->persist($request, $transaction);
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $transaction = Tranzakcio::find($id);

        if (!$transaction) {
            return redirect('/fooldal')->withErrors(['error' => 'A tranzakció nem található!']);
        }

        $user = $this->sessionUser($request);

        if ((int) $transaction->felhasznaloid !== (int) $user->id) {
            return redirect('/fooldal')->withErrors(['error' => 'Nincs jogod ezt a tranzakciót törölni!']);
        }

        $transaction->delete();

        return redirect('/fooldal')->with('success', 'Tranzakció sikeresen törölve!');
    }

    private function persist(Request $request, ?Tranzakcio $transaction = null): RedirectResponse
    {
        $request->validate([
            'kategoria' => ['required', 'string', 'max:50'],
            'osszeg' => ['required'],
            'penznem' => ['required', 'string', 'max:10'],
            'rogzites' => ['required', 'date'],
            'tipus' => ['required', 'string'],
            'megjegyzes' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $this->sessionUser($request);
        $categoryName = trim((string) $request->input('kategoria'));
        $type = $this->transactionInputService->resolveTransactionType($request->input('tipus'));
        $amount = $this->transactionInputService->normalizeAmountValue($request->input('osszeg'));

        if ($categoryName === '') {
            return back()->withErrors(['kategoria' => 'Adj meg egy kategóriát.'])->withInput();
        }

        if ($amount === null || $amount < 0) {
            return back()->withErrors(['osszeg' => 'Adj meg egy érvényes összeget.'])->withInput();
        }

        if (!$type) {
            return back()->withErrors(['tipus' => 'Válassz tranzakció típust (költség vagy bevétel).'])->withInput();
        }

        $category = $this->categoryService->findOrCreate($categoryName, $user->id, $type);
        $currency = $this->currencyService->findOrCreate((string) $request->input('penznem'));

        if (!$currency) {
            return back()->withErrors(['penznem' => 'A pénznem 3 betűs kód legyen (például HUF, EUR, USD).'])->withInput();
        }

        $payload = [
            'felhasznaloid' => $user->id,
            'kategoriaid' => $category->id,
            'tipus' => $type,
            'penznemid' => $currency->id,
            'osszeg' => $amount,
            'megjegyzes' => $request->input('megjegyzes'),
            'rogzites' => $request->input('rogzites'),
        ];

        if ($transaction) {
            $transaction->update($payload);

            return redirect('/fooldal')->with('success', 'Tranzakció sikeresen szerkesztve!');
        }

        Tranzakcio::create($payload);

        return redirect('/fooldal')->with('success', 'Tranzakció sikeresen hozzáadva!');
    }
}
