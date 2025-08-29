<?php

namespace App\Livewire;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class EditTransaksi extends Component
{
    // Properti untuk data transaksi yang akan diedit
    public Transaksi $transaksi;
    public $kode;
    public $tanggaltransaksi;
    public $bayar;
    public $kembalian;
    public $total;

    // Properti untuk keranjang belanja
    public $cartItems = [];

    // Properti untuk fitur pencarian produk
    public $searchProduk = '';
    public $searchResults = [];
    public $showProdukList = false;

    // Mount lifecycle hook untuk memuat data saat inisialisasi
    public function mount(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi;
        $this->kode = $transaksi->kode;
        $this->tanggaltransaksi = $transaksi->tanggaltransaksi->format('Y-m-d');
        $this->bayar = $transaksi->bayar;
        $this->total = $transaksi->total;
        $this->kembalian = $transaksi->kembalian;

        // Memuat detail transaksi ke dalam keranjang belanja
        $this->cartItems = $transaksi->detailTransaksis->map(function ($detail) {
            return [
                'id' => $detail->id, // ID detail transaksi
                'produk_id' => $detail->produk_id,
                'nama_produk' => $detail->produk->nama,
                'kode_produk' => $detail->produk->kode,
                'harga' => $detail->harga,
                // Stok awal + jumlah detail saat ini untuk validasi
                'stok' => optional($detail->produk)->stok + $detail->jumlah,
                'jumlah' => $detail->jumlah,
                'subtotal' => $detail->subtotal,
            ];
        })->toArray();
    }

    // Aturan validasi
    protected function rules()
    {
        return [
            'cartItems' => 'required|array|min:1',
            'bayar' => 'required|integer|min:' . $this->total,
        ];
    }

    // Fungsi saat properti di-update
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

    // Hitung total transaksi dan kembalian
    public function calculateTotal()
    {
        $this->total = collect($this->cartItems)->sum('subtotal');
        $this->kembalian = $this->bayar > $this->total ? $this->bayar - $this->total : 0;
    }

    // Tambahkan produk ke keranjang
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
            'id' => null, // null untuk item baru
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

    // Perbarui jumlah produk dalam keranjang
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

    // Hapus produk dari keranjang
    public function removeFromCart($index)
    {
        if (!isset($this->cartItems[$index])) {
            return;
        }

        $item = $this->cartItems[$index];

        if ($item['id']) {
            $detailTransaksi = DetailTransaksi::find($item['id']);
            if ($detailTransaksi) {
                $produk = Produk::find($detailTransaksi->produk_id);
                if ($produk) {
                    $produk->stok += $detailTransaksi->jumlah;
                    $produk->save();
                }
                $detailTransaksi->delete();
            }
        }

        unset($this->cartItems[$index]);
        $this->cartItems = array_values($this->cartItems);
        $this->calculateTotal();

        $this->dispatch('swal:success', [
            'title' => 'Berhasil!',
            'text' => 'Produk berhasil dihapus dari transaksi.',
            'icon' => 'success'
        ]);
    }

    // Perbarui transaksi dan detail transaksi
    public function update()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $this->transaksi->update([
                'total' => $this->total,
                'bayar' => $this->bayar,
                'kembalian' => $this->kembalian,
            ]);

            $existingDetailIds = $this->transaksi->detailTransaksis->pluck('id')->toArray();
            $newDetailIds = [];

            foreach ($this->cartItems as $item) {
                if ($item['id']) {
                    $detail = DetailTransaksi::find($item['id']);
                    if ($detail) {
                        $stokChange = $item['jumlah'] - $detail->jumlah;
                        if ($stokChange !== 0) {
                            $produk = Produk::find($detail->produk_id);
                            if ($produk) {
                                $produk->stok -= $stokChange;
                                $produk->save();
                            }
                        }
                        $detail->update([
                            'jumlah' => $item['jumlah'],
                            'subtotal' => $item['subtotal'],
                        ]);
                        $newDetailIds[] = $detail->id;
                    }
                } else {
                    $produk = Produk::find($item['produk_id']);
                    if ($produk) {
                        $produk->stok -= $item['jumlah'];
                        $produk->save();
                    }

                    $detail = DetailTransaksi::create([
                        'transaksi_id' => $this->transaksi->id,
                        'produk_id' => $item['produk_id'],
                        'jumlah' => $item['jumlah'],
                        'harga' => $item['harga'],
                        'subtotal' => $item['subtotal'],
                    ]);
                    $newDetailIds[] = $detail->id;
                }
            }

            $itemsToDelete = array_diff($existingDetailIds, $newDetailIds);
            if (!empty($itemsToDelete)) {
                $detailsToDelete = DetailTransaksi::whereIn('id', $itemsToDelete)->get();
                foreach($detailsToDelete as $detail) {
                    $produk = Produk::find($detail->produk_id);
                    if ($produk) {
                        $produk->stok += $detail->jumlah;
                        $produk->save();
                    }
                    $detail->delete();
                }
            }

            DB::commit();

            // Tambahkan SweetAlert di sini
            $this->dispatch('swal:success', [
                'title' => 'Berhasil!',
                'text' => 'Transaksi berhasil diperbarui!',
                'icon' => 'success',
                'redirect' => route('transaksi.index'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:error', [
                'title' => 'Gagal!',
                'text' => 'Gagal memperbarui transaksi. Detail: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi.edit-transaksi')
            ->layout('layouts.app');
    }
}
