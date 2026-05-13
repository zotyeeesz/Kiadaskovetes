<?php

namespace App\Http\Controllers;

use App\Models\felhasznalo;
use Illuminate\View\View;

class UtilityController extends Controller
{
    public function testPage(): View
    {
        return view('teszt', [
            'felhasznalo' => felhasznalo::all(),
        ]);
    }
}
