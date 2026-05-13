<?php

namespace App\Http\Middleware;

use App\Models\felhasznalo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionUserIsAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $sessionUser = $request->session()->get('user');

        if (!$sessionUser) {
            return $this->unauthenticatedResponse($request);
        }

        $user = felhasznalo::find($sessionUser->id);

        if (!$user) {
            $request->session()->flush();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A munkamenet lejárt, jelentkezz be újra.',
                ], 401);
            }

            return redirect('/login')->withErrors([
                'A munkamenet lejárt, jelentkezz be újra.',
            ]);
        }

        $request->attributes->set('session_user', $user);

        return $next($request);
    }

    private function unauthenticatedResponse(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Bejelentkezés szükséges.',
            ], 401);
        }

        return redirect('/login');
    }
}
