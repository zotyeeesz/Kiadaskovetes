<?php

namespace App\Services;

use App\Models\penznem;

class CurrencyService
{
    public function normalizeCurrencyCode(?string $currency): string
    {
        $normalized = strtoupper(trim((string) $currency));

        return preg_replace('/\s+/', '', $normalized);
    }

    public function ensureDefaultCurrenciesExist(): void
    {
        foreach ($this->defaultCurrencyCodes() as $code) {
            penznem::firstOrCreate(['nev' => $code], ['nev' => $code]);
        }
    }

    public function findOrCreate(string $currencyInput): ?penznem
    {
        $currencyCode = $this->normalizeCurrencyCode($currencyInput);

        if ($currencyCode === '') {
            return null;
        }

        $existingCurrency = penznem::whereRaw('UPPER(TRIM(nev)) = ?', [$currencyCode])->first();
        if ($existingCurrency) {
            return $existingCurrency;
        }

        if (!preg_match('/^[A-Z]{3}$/', $currencyCode)) {
            return null;
        }

        return penznem::create([
            'nev' => $currencyCode,
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function defaultCurrencyCodes(): array
    {
        return [
            'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD',
            'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF',
            'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN',
            'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC',
            'CUC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD',
            'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL',
            'GGP', 'GHS', 'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD',
            'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'IMP', 'INR',
            'IQD', 'IRR', 'ISK', 'JEP', 'JMD', 'JOD', 'JPY', 'KES',
            'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT',
            'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL',
            'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRU', 'MUR', 'MVR',
            'MWK', 'MXN', 'MYR', 'MZN', 'NAD', 'NGN', 'NIO', 'NOK',
            'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR',
            'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR',
            'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS',
            'SRD', 'SSP', 'STN', 'SVC', 'SYP', 'SZL', 'THB', 'TJS',
            'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH',
            'UGX', 'USD', 'UYU', 'UZS', 'VES', 'VND', 'VUV', 'WST',
            'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW', 'ZWL',
        ];
    }
}
