<?php

namespace App\Http\Controllers\Concerns;

use App\Models\felhasznalo;
use Illuminate\Http\Request;

trait ResolvesSessionUser
{
    protected function sessionUser(Request $request): felhasznalo
    {
        return $request->attributes->get('session_user');
    }
}
