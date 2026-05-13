<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesSessionUser;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use ResolvesSessionUser;

    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {
    }

    public function index(Request $request): View
    {
        return view('fooldal', $this->dashboardService->buildViewData(
            $this->sessionUser($request),
            $request->query()
        ));
    }
}
