<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\penznem;
use App\Models\arbazis;
use Illuminate\Support\Facades\Http;

class ArfbazisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fallback: alapértelmezett árfolyamok (gyorsabb és biztosabb)
        $arfolyamok = [
            'HUF' => 1.0,      // Forint (alappénznem)
            'EUR' => 415.0,    // 1 EUR = 415 HUF
            'USD' => 380.0,    // 1 USD = 380 HUF
            'GBP' => 480.0,    // 1 GBP = 480 HUF
            'CHF' => 420.0,    // 1 CHF = 420 HUF
            'JPY' => 2.5,      // 1 JPY = 2,5 HUF
            'CNY' => 52.0,     // 1 CNY = 52 HUF
            'AUD' => 250.0,    // 1 AUD = 250 HUF
            'CAD' => 280.0,    // 1 CAD = 280 HUF
            'DKK' => 55.7,     // 1 DKK = 55,7 HUF
            'NOK' => 37.0,     // 1 NOK = 37 HUF
            'SEK' => 38.0,     // 1 SEK = 38 HUF
            'PLN' => 95.0,     // 1 PLN = 95 HUF
            'CZK' => 16.0,     // 1 CZK = 16 HUF
            'RON' => 83.0,     // 1 RON = 83 HUF
            'BGN' => 212.0,    // 1 BGN = 212 HUF
            'HRK' => 55.5,     // 1 HRK = 55,5 HUF
            'RUB' => 3.5,      // 1 RUB = 3,5 HUF
            'INR' => 4.5,      // 1 INR = 4,5 HUF
            'MXN' => 22.0,     // 1 MXN = 22 HUF
            'SGD' => 283.0,    // 1 SGD = 283 HUF
            'HKD' => 48.5,     // 1 HKD = 48,5 HUF
            'NZD' => 230.0,    // 1 NZD = 230 HUF
            'ZAR' => 21.0,     // 1 ZAR = 21 HUF
            'AED' => 103.5,    // 1 AED = 103,5 HUF
            'SAR' => 101.0,    // 1 SAR = 101 HUF
        ];

        foreach ($arfolyamok as $penznev => $arfolyam) {
            $penz = penznem::where('nev', $penznev)->first();

            if ($penz) {
                arbazis::firstOrCreate(
                    ['penznemid' => $penz->id],
                    ['arfolyam' => $arfolyam]
                );
            }
        }

        echo "✓ Alapértelmezett árfolyamok betöltve.\n";
    }
}
