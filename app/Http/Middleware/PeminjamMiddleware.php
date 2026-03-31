<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PeminjamMiddleware
{
    /**
     * Handle an incoming request.
     * Hanya role 'peminjam' yang boleh lewat.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Database uses role 'user' for regular users; accept both for safety
        if (!in_array(Auth::user()->role, ['peminjam', 'user'])) {
            abort(403, 'Akses ditolak. Halaman ini khusus Peminjam.');
        }

        return $next($request);
    }
}