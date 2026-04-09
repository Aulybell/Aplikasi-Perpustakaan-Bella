<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' =>  'required|email|unique:users,email',
            'password' => 'required',
            'alamat' => 'required'
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'alamat.required' => 'Alamat wajib diisi'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'role' => 'user'
        ]);
        session()->flash('success', 'Registrasi berhasil!');
        return redirect()->route('register');
    }
}
