<?php

namespace App\Livewire;

use App\Models\Kategori;
use Livewire\Component;

class CreateKategori extends Component
{
    public $nama;

    protected $rules = [
        'nama' => 'required|string|min:3|unique:kategoris,nama',
    ];

    public function store()
    {
        $this->validate();

        Kategori::create([
            'nama' => $this->nama,
        ]);

        session()->flash('success', 'Kategori berhasil ditambahkan!');
        return redirect()->route('kategori.index');
    }

    public function render()
    {
        return view('livewire.kategori.create-kategori')
            ->layout('layouts.app');
    }
}
