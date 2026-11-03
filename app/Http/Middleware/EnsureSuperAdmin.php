<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            if (Route::has('login')) {
                return redirect()->route('login');
            }

            abort(401, 'Silakan login terlebih dahulu.');
        }

        abort_unless($user->isSuperAdmin(), 403, 'Halaman ini hanya untuk super admin.');

        return $next($request);
    }
}