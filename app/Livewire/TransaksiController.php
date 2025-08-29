<?php

namespace App\Livewire;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Component
{
    use WithPagination;

    // Properti untuk filter, sorting dan pencarian
    public $search = '';
    public $sortField = 'tanggaltransaksi';
    public $sortDirection = 'desc';
    public $startDate;
    public $endDate;

    // Properti untuk modal
    public $showDetailModal = false;
    public $selectedTransaksi;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    // Fungsi untuk menghapus transaksi
    public function delete($id)
    {
        // Gunakan transaction untuk memastikan operasi atomic
        DB::beginTransaction();

        try {
            $transaksi = Transaksi::with('detailTransaksis.produk')->find($id);

            if ($transaksi) {
                // Iterasi setiap item dalam transaksi
                foreach ($transaksi->detailTransaksis as $detail) {
                    $produk = $detail->produk;

                    // Cek apakah produknya ada
                    if ($produk) {
                        // Tambahkan kembali stok produk
                        $produk->stok += $detail->jumlah;
                        $produk->save();
                    }

                    // Hapus detail transaksi
                    $detail->delete();
                }

                // Hapus transaksi setelah semua detail dan stok dikembalikan
                $transaksi->delete();

                DB::commit();

                $this->dispatch('swal:success', [
                    'title' => 'Terhapus!',
                    'text' => 'Transaksi berhasil dihapus dan stok produk telah dikembalikan.',
                    'icon' => 'success'
                ]);
            } else {
                DB::rollBack();
                $this->dispatch('swal:error', [
                    'title' => 'Gagal!',
                    'text' => 'Transaksi tidak ditemukan.',
                    'icon' => 'error'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:error', [
                'title' => 'Gagal!',
                'text' => 'Gagal menghapus transaksi. Detail: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    public function showDetail(Transaksi $transaksi)
    {
        $this->selectedTransaksi = $transaksi;
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedTransaksi = null;
    }

    public function render()
    {
        $query = Transaksi::query()
            ->where('kode', 'like', '%' . $this->search . '%')
            ->orWhere('total', 'like', '%' . $this->search . '%')
            ->orWhere('bayar', 'like', '%' . $this->search . '%');

        if ($this->startDate) {
            $query->whereDate('tanggaltransaksi', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('tanggaltransaksi', '<=', $this->endDate);
        }

        $transaksis = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.transaksi.transaksi-controller', compact('transaksis'))
            ->layout('layouts.app');
    }
}
