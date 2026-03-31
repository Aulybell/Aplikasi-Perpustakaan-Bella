<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PetugasMiddleware
{
    /**
     * Handle an incoming request.
     * Role 'admin' dan 'petugas' boleh lewat.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!in_array(Auth::user()->role, ['admin', 'petugas'])) {
            abort(403, 'Akses ditolak. Halaman ini khusus Petugas dan Admin.');
        }

        return $next($request);
    }
}