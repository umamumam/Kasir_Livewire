<?php

namespace App\Livewire;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class CreateTransaksi extends Component
{
    // Properti untuk data transaksi
    public $tanggaltransaksi;
    public $bayar = 0;
    public $kembalian = 0;
    public $total = 0;

    // Properti untuk keranjang belanja
    public $cartItems = [];

    // Properti untuk fitur pencarian produk
    public $searchProduk = '';
    public $searchResults = [];
    public $showProdukList = false;

    // Mount lifecycle hook
    public function mount()
    {
        $this->tanggaltransaksi = now()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'cartItems' => 'required|array|min:1',
            'bayar' => 'required|integer|min:' . $this->total,
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'searchProduk') {
            $this->searchResults = Produk::where('nama', 'like', '%' . $this->searchProduk . '%')
                ->orWhere('kode', 'like', '%' . $this->searchProduk . '%')
                ->limit(10)
                ->get();
            $this->showProdukList = !empty($this->searchResults);
        }
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->cartItems)->sum('subtotal');
        $this->kembalian = $this->bayar > $this->total ? $this->bayar - $this->total : 0;
    }

    public function addProdukToCart($produkId)
    {
        $produk = Produk::find($produkId);
        $existingItem = collect($this->cartItems)->firstWhere('produk_id', $produk->id);

        if ($existingItem) {
            $this->dispatch('swal:error', [
                'title' => 'Gagal!',
                'text' => 'Produk sudah ada di keranjang.',
                'icon' => 'error'
            ]);
            return;
        }

        if ($produk->stok < 1) {
            $this->dispatch('swal:error', [
                'title' => 'Gagal!',
                'text' => 'Stok produk tidak mencukupi.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->cartItems[] = [
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama,
            'kode_produk' => $produk->kode,
            'harga' => $produk->harga_jual,
            'stok' => $produk->stok,
            'jumlah' => 1,
            'subtotal' => $produk->harga_jual * 1,
        ];

        $this->searchProduk = '';
        $this->searchResults = [];
        $this->showProdukList = false;
        $this->calculateTotal();
    }

    public function updateJumlah($index, $newJumlah)
    {
        if (!isset($this->cartItems[$index])) return;

        $item = $this->cartItems[$index];
        $stok = $item['stok'];
        $harga = $item['harga'];
        $newJumlah = max(1, $newJumlah);

        if ($newJumlah > $stok) {
            $this->dispatch('swal:error', [
                'title' => 'Gagal!',
                'text' => 'Jumlah melebihi stok yang tersedia.',
                'icon' => 'error'
            ]);
            $this->cartItems[$index]['jumlah'] = $stok;
        } else {
            $this->cartItems[$index]['jumlah'] = $newJumlah;
        }
        $this->cartItems[$index]['subtotal'] = $this->cartItems[$index]['jumlah'] * $harga;
        $this->calculateTotal();
    }

    public function removeFromCart($index)
    {
        unset($this->cartItems[$index]);
        $this->cartItems = array_values($this->cartItems);
        $this->calculateTotal();
    }

    public function store()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $today = Carbon::today();
            $lastTransaksi = Transaksi::whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastTransaksi) {
                $parts = explode('/', $lastTransaksi->kode);
                if (count($parts) > 1 && is_numeric($parts[1])) {
                    $nextNumber = intval($parts[1]) + 1;
                }
            }

            $kodeTransaksi = $today->format('Ymd') . '/' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);

            $transaksi = Transaksi::create([
                'kode' => $kodeTransaksi,
                'total' => $this->total,
                'bayar' => $this->bayar,
                'kembalian' => $this->kembalian,
                'tanggaltransaksi' => now(),
            ]);

            foreach ($this->cartItems as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                ]);

                $produk = Produk::find($item['produk_id']);
                $produk->stok -= $item['jumlah'];
                $produk->save();
            }

            DB::commit();

            // Tambahkan SweetAlert di sini
            $this->dispatch('swal:success', [
                'title' => 'Berhasil!',
                'text' => 'Transaksi berhasil disimpan!',
                'icon' => 'success',
                'redirect' => route('transaksi.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:error', [
                'title' => 'Gagal!',
                'text' => 'Gagal menyimpan transaksi. Detail: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi.create-transaksi')
            ->layout('layouts.app');
    }
}
