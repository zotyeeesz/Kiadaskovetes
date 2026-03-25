<?php

namespace App\Services;

use App\Models\arbazis;
use App\Models\penznem;
use App\Models\Tranzakcio;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    /**
     * Az alapértelmezett pénznem (HUF)
     */
    const BASE_CURRENCY = 'HUF';
    const RATE_TTL_HOURS = 6;

    /**
     * Egy HTTP kérésen belül csak egyszer próbáljunk frissíteni.
     */
    private static bool $refreshAttemptedInRequest = false;

    private function normalizeCurrency(?string $currency): string
    {
        return strtoupper(trim((string) $currency));
    }

    private function isRateFresh(?arbazis $rateRow): bool
    {
        if (!$rateRow || !$rateRow->updated_at) {
            return false;
        }

        return $rateRow->updated_at->gte(now()->subHours(self::RATE_TTL_HOURS));
    }

    private function getRateRowByCurrency(string $currency): ?arbazis
    {
        $currency = $this->normalizeCurrency($currency);

        return arbazis::whereHas('penznem', function ($q) use ($currency) {
            $q->whereRaw('UPPER(TRIM(nev)) = ?', [$currency]);
        })->first();
    }

    private function fetchLatestRates(): array
    {
        $urls = [
            'https://open.er-api.com/v6/latest/' . self::BASE_CURRENCY,
            'https://api.exchangerate-api.com/v4/latest/' . self::BASE_CURRENCY,
        ];

        foreach ($urls as $url) {
            try {
                $response = Http::timeout(10)->get($url);
                if (!$response->successful()) {
                    continue;
                }

                $payload = $response->json();
                if (isset($payload['rates']) && is_array($payload['rates']) && !empty($payload['rates'])) {
                    return $payload['rates'];
                }
            } catch (\Throwable $e) {
                // Sikertelen API hívás esetén a következő forrást próbáljuk.
            }
        }

        return [];
    }

    private function refreshRatesFromApi(): void
    {
        if (self::$refreshAttemptedInRequest) {
            return;
        }
        self::$refreshAttemptedInRequest = true;

        try {
            $penznemek = penznem::all();
            if ($penznemek->isEmpty()) {
                return;
            }

            $rates = $this->fetchLatestRates();
            if (empty($rates)) {
                return;
            }

            foreach ($penznemek as $penznem) {
                $code = $this->normalizeCurrency($penznem->nev);

                if ($code === self::BASE_CURRENCY) {
                    arbazis::updateOrCreate(
                        ['penznemid' => $penznem->id],
                        ['arfolyam' => 1.0]
                    );
                    continue;
                }

                if (!isset($rates[$code]) || $rates[$code] <= 0) {
                    continue;
                }

                $invertedRate = 1 / $rates[$code];

                arbazis::updateOrCreate(
                    ['penznemid' => $penznem->id],
                    ['arfolyam' => $invertedRate]
                );
            }
        } catch (\Throwable $e) {
            // Ha nem sikerül frissíteni, maradnak a jelenlegi adatbázis árfolyamok.
        }
    }

    private function refreshRatesIfNeeded(array $requiredCurrencies = []): void
    {
        try {
            $currencies = collect($requiredCurrencies)
                ->map(fn($c) => $this->normalizeCurrency($c))
                ->filter()
                ->reject(fn($c) => $c === self::BASE_CURRENCY)
                ->unique()
                ->values();

            if ($currencies->isEmpty()) {
                $hasFreshRates = arbazis::where('updated_at', '>=', now()->subHours(self::RATE_TTL_HOURS))->exists();
                if (!$hasFreshRates) {
                    $this->refreshRatesFromApi();
                }
                return;
            }

            $needRefresh = $currencies->contains(function ($currency) {
                $rateRow = $this->getRateRowByCurrency($currency);
                return !$this->isRateFresh($rateRow);
            });

            if ($needRefresh) {
                $this->refreshRatesFromApi();
            }
        } catch (\Throwable $e) {
            // Ha a frissítés-ellenőrzés hibázik, fallback az adatbázisban levő árfolyam.
        }
    }

    /**
     * Konvertál egy összeget egyik pénznemből a másikba
     * 
     * @param float $amount Az átváltandó összeg
     * @param string $fromCurrency Az eredeti pénznem kódja
     * @param string $toCurrency A célpénznem kódja
     * @return float|null Az átváltott összeg, vagy null ha sikertelen
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency): ?float
    {
        $fromCurrency = $this->normalizeCurrency($fromCurrency);
        $toCurrency = $this->normalizeCurrency($toCurrency);

        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        try {
            // Ha az egyik HUF, könnyebb kezelhetőség
            if ($fromCurrency === self::BASE_CURRENCY) {
                // HUF-ból bármi másra konvertálás: ha 1 CUR = X HUF, akkor HUF -> CUR osztás.
                $rate = $this->getRate($toCurrency);
                return ($rate && $rate > 0) ? $amount / $rate : null;
            } elseif ($toCurrency === self::BASE_CURRENCY) {
                // Bármiből HUF-ra konvertálás
                // 1 fromCurrency = ? HUF (ezt tároljuk az adatbázisban)
                $rate = $this->getRate($fromCurrency);
                return $rate ? $amount * $rate : null;
            } else {
                // Bármiből bármivé konvertálás
                // Lépés 1: konvertál alapérték (HUF)
                $amountInHuf = $this->convert($amount, $fromCurrency, self::BASE_CURRENCY);
                // Lépés 2: HUF-ból a célpénznembe
                return $this->convert($amountInHuf, self::BASE_CURRENCY, $toCurrency);
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Lekérdezi az árfolyamot az adatbázisból
     * 
     * @param string $currency A pénznem kódja
     * @return float|null Az árfolyam (1 currency = ? HUF), vagy null
     */
    public function getRate(string $currency): ?float
    {
        $currency = $this->normalizeCurrency($currency);

        if ($currency === self::BASE_CURRENCY) {
            return 1.0; // 1 HUF = 1 HUF
        }

        try {
            $this->refreshRatesIfNeeded([$currency]);

            $penznem = penznem::whereRaw('UPPER(TRIM(nev)) = ?', [$currency])->first();
             
            if (!$penznem) {
                return null;
            }

            $arfolyam = arbazis::where('penznemid', $penznem->id)->first();
             
            return $arfolyam ? (float) $arfolyam->arfolyam : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Összes tranzakciót konvertál forintra
     * 
     * @param Collection $tranzakciok Az Eloquent collection tranzakcióinkkal
     * @return Collection Az eredeti tranzakciók + osszeghuf mező
     */
    public function convertAllToHUF(Collection $tranzakciok): Collection
    {
        $currencies = $tranzakciok
            ->map(fn($t) => $this->normalizeCurrency($t->penznem->nev ?? ''))
            ->filter()
            ->all();

        $this->refreshRatesIfNeeded($currencies);

        $arfolyamok = arbazis::with('penznem')->get()->keyBy('penznemid');

        return $tranzakciok->map(function($t) use ($arfolyamok) {
            $arfolyam = $arfolyamok->get($t->penznemid);
            $rate = $arfolyam ? (float) $arfolyam->arfolyam : $this->getRate($t->penznem->nev ?? self::BASE_CURRENCY);
            if (!$rate || $rate <= 0) {
                $rate = 1;
            }
            $t->osszeghuf = $t->osszeg * $rate;
            return $t;
        });
    }

    /**
     * Lekéri az összes árfolyamot keyBy pénznemid
     * 
     * @return Collection
     */
    public function getAllRates(): Collection
    {
        $this->refreshRatesIfNeeded();
        return arbazis::with('penznem')->get()->keyBy('penznemid');
    }

    /**
     * Kategória szerinti összegzés forintra átváltva
     * 
     * @param Collection $tranzakciok
     * @return Collection
     */
    public function getCategoryTotalsInHUF(Collection $tranzakciok): Collection
    {
        $arfolyamok = $this->getAllRates();
        
        $grouped = $tranzakciok->groupBy('kategoriaid');
        
        $result = collect();
        
        foreach ($grouped as $katId => $items) {
            $katName = $items->first()->kategoria->nev ?? '-';
            $totalForCat = $items->sum(function($t) use ($arfolyamok) {
                $arfolyam = $arfolyamok->get($t->penznemid);
                $rate = $arfolyam ? $arfolyam->arfolyam : 1;
                return $t->osszeg * $rate;
            });
            
            $result->push((object) ['name' => $katName, 'total' => $totalForCat]);
        }
        
        return $result->sortByDesc('total')->values();
    }
}
