<?php

namespace App\Livewire;

use App\Models\Kategori;
use App\Models\Produk;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class ProdukController extends Component
{
    use WithPagination;

    // Properti publik untuk menyimpan data form
    public $produkId;
    public $nama;
    public $kode;
    public $harga_beli;
    public $harga_jual;
    public $stok;
    public $kategori_id;

    // Properti untuk status mode
    public $isEditing = false;
    public $isCreating = false;

    // Properti untuk modal
    public $showModal = false;

    // Properti untuk pencarian
    public $search = '';

    // Properti untuk sorting
    public $sortField = 'id';
    public $sortDirection = 'desc';

    // Aturan validasi
    protected function rules()
    {
        return [
            'nama' => 'required|string|min:3',
            'kode' => ['required', 'string', 'min:3', Rule::unique('produks')->ignore($this->produkId)],
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
        ];
    }

    // Reset properti
    public function resetForm()
    {
        $this->produkId = null;
        $this->nama = '';
        $this->kode = '';
        $this->harga_beli = null;
        $this->harga_jual = null;
        $this->stok = null;
        $this->kategori_id = null;
        $this->resetValidation();
    }

    // Fungsi untuk sorting
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    // Buka modal untuk membuat produk baru
    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
        $this->isEditing = false;
        $this->showModal = true;
    }

    // Simpan produk baru
    public function store()
    {
        $validatedData = $this->validate();
        Produk::create($validatedData);

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('swal:success', [
            'title' => 'Berhasil!',
            'text' => 'Produk berhasil ditambahkan.',
            'icon' => 'success'
        ]);
    }

    // Buka modal untuk mengedit produk
    public function edit(Produk $produk)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->isCreating = false;
        $this->showModal = true;

        $this->produkId = $produk->id;
        $this->nama = $produk->nama;
        $this->kode = $produk->kode;
        $this->harga_beli = $produk->harga_beli;
        $this->harga_jual = $produk->harga_jual;
        $this->stok = $produk->stok;
        $this->kategori_id = $produk->kategori_id;
    }

    // Perbarui produk
    public function update()
    {
        $this->validate();
        $produk = Produk::findOrFail($this->produkId);
        $produk->update([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'stok' => $this->stok,
            'kategori_id' => $this->kategori_id,
        ]);

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('swal:success', [
            'title' => 'Berhasil!',
            'text' => 'Produk berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    // Hapus produk
    public function delete($id)
    {
        Produk::find($id)->delete();
        $this->dispatch('swal:success', [
            'title' => 'Terhapus!',
            'text' => 'Produk berhasil dihapus.',
            'icon' => 'success'
        ]);
    }

    // Render tampilan
    public function render()
    {
        $produks = Produk::with('kategori')
            ->where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('kode', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        $kategoris = Kategori::all();

        return view('livewire.produk', compact('produks', 'kategoris'))->layout('layouts.app');
    }
}
