<?php

namespace App\Http\Middleware;

use App\Models\felhasznalo;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionUserIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var felhasznalo|null $user */
        $user = $request->attributes->get('session_user');

        if (!$user) {
            $sessionUser = $request->session()->get('user');
            $user = $sessionUser ? felhasznalo::find($sessionUser->id) : null;
        }

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bejelentkezés szükséges.',
                ], 401);
            }

            return redirect('/login');
        }

        if ($user->email_verified_at) {
            $request->attributes->set('session_user', $user);

            return $next($request);
        }

        $request->session()->flush();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'A fiókod még nincs megerősítve. Előbb igazold az email címed.',
            ], 403);
        }

        return redirect('/login')
            ->withErrors(['A fiókod még nincs megerősítve. Előbb igazold az email címed.'])
            ->with('pending_verification_email', $user->email);
    }
}
