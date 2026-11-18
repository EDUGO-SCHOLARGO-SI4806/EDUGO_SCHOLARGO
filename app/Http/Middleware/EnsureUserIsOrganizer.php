<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Hanya pengguna dengan role "penyelenggara" yang boleh membuka halaman portal mitra.
 */
class EnsureUserIsOrganizer
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isOrganizer(), 403, 'Halaman ini khusus penyelenggara beasiswa.');

        return $next($request);
    }
}
