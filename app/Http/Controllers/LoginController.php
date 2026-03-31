<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        // jika sudah login, langsung ke halaman utama
        if (auth()->check()) {
            $role = auth()->user()->role;
            if ($role === 'user') {
                return redirect()->route('koleksi');
            }
            return redirect()->route('home');
        }
        return view('login.index');
    }

    public function proses(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ],[
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            // redirect berdasarkan role
            $role = auth()->user()->role;
            if ($role === 'user') {
                // direct redirect to koleksi, ignore any previous intended URL
                return redirect()->route('koleksi');
            }

            // for other roles keep intended behavior (if any)
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Autentikasi gagal, pastikan email dan password benar',
        ])->onlyInput('email');
    }
    public function keluar(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
