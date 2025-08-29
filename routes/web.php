<?php

use App\Livewire\Counter;
use App\Livewire\EditTransaksi;
use App\Livewire\CreateTransaksi;
use App\Livewire\ProdukController;
use App\Livewire\KategoriController;
use App\Livewire\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/page', function () {
    return view('layouts1.app');
});

Route::get('/dashboard', function () {
    return view('livewire.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/counter', Counter::class);
Route::get('/kategori', KategoriController::class);
Route::get('/produk', ProdukController::class);
Route::get('/transaksi', TransaksiController::class)->name('transaksi.index');
Route::get('/transaksi/create', CreateTransaksi::class)->name('transaksi.create');
Route::get('/transaksi/{transaksi}/edit', EditTransaksi::class)->name('transaksi.edit');
require __DIR__.'/auth.php';
