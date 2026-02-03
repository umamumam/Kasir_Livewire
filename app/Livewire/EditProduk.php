<?php

namespace App\Livewire;

use App\Models\Produk;
use App\Models\Kategori;
use Livewire\Component;
use Illuminate\Validation\Rule;

class EditProduk extends Component
{
    public $produkId;
    public $nama;
    public $kode;
    public $harga_beli;
    public $harga_jual;
    public $stok;
    public $kategori_id;

    public function mount(Produk $produk)
    {
        $this->produkId = $produk->id;
        $this->nama = $produk->nama;
        $this->kode = $produk->kode;
        $this->harga_beli = $produk->harga_beli;
        $this->harga_jual = $produk->harga_jual;
        $this->stok = $produk->stok;
        $this->kategori_id = $produk->kategori_id;
    }

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

        session()->flash('success', 'Produk berhasil diperbarui!');
        return redirect()->route('produk.index');
    }

    public function render()
    {
        $kategoris = Kategori::all();
        return view('livewire.produk.edit-produk', compact('kategoris'))
            ->layout('layouts.app');
    }
}
