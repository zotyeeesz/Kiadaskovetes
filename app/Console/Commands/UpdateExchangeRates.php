<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\penznem;
use App\Models\arbazis;
use Illuminate\Support\Facades\Http;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:update-exchange-rates';

    /**
     * The command description.
     */
    protected $description = 'Lekéri az aktuális árfolyamokat az ExchangeRate-API-ból és frissíti az adatbázist';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Árfolyamok frissítésének kezdete...');

        try {
            $updated = 0;
            $failed = 0;

            // Lekérjük az összes pénznemi kódot az adatbázisból
            $penznemek = penznem::all();

            if ($penznemek->isEmpty()) {
                $this->error('Nincsenek pénznemek az adatbázisban.');
                return 1;
            }

            // ExchangeRate-API: minden más pénznem forintra átváltása
            $baseCurrency = 'HUF';
            $targetCurrencies = $penznemek->pluck('nev')->filter(function($currency) use ($baseCurrency) {
                return $currency !== $baseCurrency;
            })->join(',');

            if (empty($targetCurrencies)) {
                $this->error('Nincsenek átváltandó pénznemek.');
                return 1;
            }

            // ExchangeRate-API endpoint
            $apiUrl = "https://api.exchangerate-api.com/v4/latest/{$baseCurrency}";

            $response = Http::timeout(15)->get($apiUrl);

            if (!$response->successful()) {
                $this->error('API hiba: ' . $response->status());
                return 1;
            }

            $data = $response->json();

            if (!isset($data['rates']) || empty($data['rates'])) {
                $this->error('Az API nem adott vissza árfolyam adatokat.');
                return 1;
            }

            $rates = $data['rates'];

            // Minden pénznemre megpróbáljuk az árfolyamot frissíteni
            foreach ($penznemek as $penznem) {
                $currency = $penznem->nev;
                
                if ($currency === $baseCurrency) {
                    // HUF alapú, 1 HUF = 1 HUF
                    arbazis::updateOrCreate(
                        ['penznemid' => $penznem->id],
                        ['arfolyam' => 1]
                    );
                    $updated++;
                    $this->line("✓ {$currency}: 1 {$currency} = 1 HUF (alapérték)");
                    continue;
                }

                if (isset($rates[$currency])) {
                    try {
                        $rate = $rates[$currency];
                        
                        // Az API direktben adja az 1 HUF = ? currency árat
                        // De mi azt szeretnénk: 1 currency = ? HUF
                        // Ezért invertáljuk: 1 currency = 1 / rate HUF
                        $invertedRate = $rate > 0 ? 1 / $rate : 0;

                        arbazis::updateOrCreate(
                            ['penznemid' => $penznem->id],
                            ['arfolyam' => $invertedRate]
                        );

                        $updated++;
                        $this->line("✓ {$currency}: 1 {$currency} = " . round($invertedRate, 4) . " HUF");
                    } catch (\Exception $e) {
                        $failed++;
                        $this->error("✗ {$currency}: " . $e->getMessage());
                    }
                } else {
                    $failed++;
                    $this->warn("⚠ {$currency}: Az API nem adott vissza árfolyamot");
                }
            }

            $this->info("\n✓ Árfolyamok sikeresen frissítve! ({$updated} frissítve, {$failed} hiba)");
            return 0;

        } catch (\Exception $e) {
            $this->error('Hiba az árfolyamok kérésekor: ' . $e->getMessage());
            return 1;
        }
    }
}
