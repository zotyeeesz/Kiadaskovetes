<?php

namespace App\Services;

use App\Models\arbazis;
use App\Models\penznem;
use App\Models\Tranzakcio;
use Illuminate\Support\Collection;

class ExchangeRateService
{
    /**
     * Az alapértelmezett pénznem (HUF)
     */
    const BASE_CURRENCY = 'HUF';

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
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        try {
            // Ha az egyik HUF, könnyebb kezelhetőség
            if ($fromCurrency === self::BASE_CURRENCY) {
                // HUF-ból bármi másra konvertálás
                $rate = $this->getRate($toCurrency);
                return $rate ? $amount * $rate : null;
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
        if ($currency === self::BASE_CURRENCY) {
            return 1.0; // 1 HUF = 1 HUF
        }

        try {
            $penznem = penznem::where('nev', $currency)->first();
            
            if (!$penznem) {
                return null;
            }

            $arfolyam = arbazis::where('penznemid', $penznem->id)->first();
            
            return $arfolyam ? $arfolyam->arfolyam : null;
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
        $arfolyamok = arbazis::with('penznem')->get()->keyBy('penznemid');

        return $tranzakciok->map(function($t) use ($arfolyamok) {
            $arfolyam = $arfolyamok->get($t->penznemid);
            $rate = $arfolyam ? $arfolyam->arfolyam : 1;
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
