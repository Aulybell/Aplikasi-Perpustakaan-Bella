<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PinjamController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

// ── Public routes (tidak perlu login) ─────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/users', [UserController::class, 'index'])->name('users');

Route::get('register', [RegisterController::class, 'index'])->name('register');
Route::post('register', [RegisterController::class, 'store'])->name('register.store');

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'proses'])->name('login.proses');
Route::post('login/keluar', [LoginController::class, 'keluar'])->name('login.keluar');

// ── Admin only ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/users', fn() => view('users.index'))->name('users');
    Route::get('/penjaga', fn() => view('penjaga.index'))->name('penjaga');
    Route::get('/laporan', fn() => view('laporan.index'))->name('laporan');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
});

// ── Admin + Petugas ────────────────────────────────────────────────────────
Route::middleware(['auth', 'petugas'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/buku', fn() => view('buku.index'))->name('buku');
    Route::get('/pinjam', fn() => view('pinjam.index'))->name('pinjam');
    Route::resource('kategori', KategoriController::class);
    Route::get('/laporan', fn() => view('laporan.index'))->name('laporan');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
    Route::get('/ulasan-admin', fn() => view('ulasan.admin'))->name('ulasan.admin');
});

// ── Peminjam only ──────────────────────────────────────────────────────────
Route::middleware(['auth', 'user'])->group(function () {
 
    Route::get('/koleksi',   fn() => view('koleksi.index'))->name('koleksi');
    Route::get('/riwayat',   fn() => view('riwayat.index'))->name('riwayat');
    Route::get('/favorites', fn() => view('favorites.index'))->name('favorites');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');
 
    // ── Keranjang ──────────────────────────────────────────────
    Route::get   ('/keranjang',         [KeranjangController::class, 'index'])     ->name('keranjang.index');
    Route::post  ('/keranjang/tambah',  [KeranjangController::class, 'tambah'])    ->name('keranjang.tambah');
    Route::delete('/keranjang/{id}',    [KeranjangController::class, 'hapus'])     ->name('keranjang.hapus');
    Route::delete('/keranjang',         [KeranjangController::class, 'hapusSemua'])->name('keranjang.hapusSemua');
 
    // ── Pinjam ─────────────────────────────────────────────────
    // Pinjam satu buku
    Route::get ('/pinjam/create/{buku}', [PinjamController::class, 'create'])->name('pinjam.create');
    Route::post('/pinjam',               [PinjamController::class, 'store']) ->name('pinjam.store');
 
    // Step 1: GET keranjang → halaman form/konfirmasi
    Route::get ('/pinjam/create-multiple', [PinjamController::class, 'createMultiple'])->name('pinjam.create.multiple');
 
    // Step 2: POST form → simpan ke database
    Route::post('/pinjam/multiple',        [PinjamController::class, 'storeMultiple']) ->name('pinjam.store.multiple');
 
 
    // ── Ulasan ─────────────────────────────────────────────────
    Route::get   ('/ulasan/{buku}',    [UlasanController::class, 'show'])   ->name('ulasan.show');
    Route::post  ('/ulasan/{buku}',    [UlasanController::class, 'store'])  ->name('ulasan.store');
    Route::delete('/delete-ulasan/{ulasan}',  [UlasanController::class, 'destroy'])->name('ulasan.delete');
});

// ── Authenticated users (admin/petugas/peminjam) can print pinjam slips ──
Route::middleware(['auth'])->get('/pinjam/print/{pinjam}', [PinjamController::class, 'print'])->name('pinjam.print');
