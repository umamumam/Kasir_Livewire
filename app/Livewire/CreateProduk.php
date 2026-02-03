<?php

namespace App\Livewire;

use App\Models\Produk;
use App\Models\Kategori;
use Livewire\Component;

class CreateProduk extends Component
{
    public $nama;
    public $kode;
    public $harga_beli;
    public $harga_jual;
    public $stok;
    public $kategori_id;

    protected function rules()
    {
        return [
            'nama' => 'required|string|min:3',
            'kode' => 'required|string|min:3|unique:produks,kode',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
        ];
    }

    public function store()
    {
        $this->validate();

        Produk::create([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'stok' => $this->stok,
            'kategori_id' => $this->kategori_id,
        ]);

        session()->flash('success', 'Produk berhasil ditambahkan!');
        return redirect()->route('produk.index');
    }

    public function render()
    {
        $kategoris = Kategori::all();
        return view('livewire.produk.create-produk', compact('kategoris'))
            ->layout('layouts.app');
    }
}
