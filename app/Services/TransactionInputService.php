<?php

namespace App\Services;

class TransactionInputService
{
    public function normalizeAmountValue(mixed $amount): ?float
    {
        if ($amount === null) {
            return null;
        }

        $normalized = str_replace([' ', ','], ['', '.'], trim((string) $amount));

        if ($normalized === '' || !is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    public function normalizeTransactionType(?string $type): string
    {
        return mb_strtolower(trim((string) $type));
    }

    public function resolveTransactionType(?string $type): ?string
    {
        $normalized = $this->normalizeTransactionType($type);

        return in_array($normalized, ['koltseg', 'bevetel'], true) ? $normalized : null;
    }

    public function normalizeSearchText(?string $value): string
    {
        return trim((string) $value);
    }
}
