<?php

use App\Livewire\Counter;
use App\Livewire\EditTransaksi;
use App\Livewire\CreateTransaksi;
use App\Livewire\ProdukController;
use App\Livewire\KategoriController;
use App\Livewire\TransaksiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/page', function () {
    return view('layouts1.app');
});

// Route::get('/dashboard', function () {
//     return view('livewire.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Kategori Routes
    Route::get('/kategori', KategoriController::class)->name('kategori.index');
    Route::get('/kategori/create', \App\Livewire\CreateKategori::class)->name('kategori.create');
    Route::get('/kategori/{kategori}/edit', \App\Livewire\EditKategori::class)->name('kategori.edit');

    // Produk Routes
    Route::get('/produk', ProdukController::class)->name('produk.index');
    Route::get('/produk/create', \App\Livewire\CreateProduk::class)->name('produk.create');
    Route::get('/produk/{produk}/edit', \App\Livewire\EditProduk::class)->name('produk.edit');
    Route::get('/transaksi', TransaksiController::class)->name('transaksi.index');
    Route::get('/transaksi/create', CreateTransaksi::class)->name('transaksi.create');
    Route::get('/transaksi/{transaksi}/edit', EditTransaksi::class)->name('transaksi.edit');
    Route::get('/nota/{transaksiId}', [NotaController::class, 'cetakNota'])->name('transaksi.nota');
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.transaksi');
});
Route::get('/counter', Counter::class);



require __DIR__ . '/auth.php';
