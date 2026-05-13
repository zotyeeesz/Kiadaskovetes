<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ExchangeRateController extends Controller
{
    public function showRefreshInfo(): JsonResponse
    {
        return response()->json([
            'message' => 'Az árfolyamok frissítési parancs elindítva.',
            'info' => 'Kérjük, futtasd le az alábbi parancsot a terminálban: php artisan app:update-exchange-rates',
        ]);
    }
}
