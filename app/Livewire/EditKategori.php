<?php

namespace App\Livewire;

use App\Models\Kategori;
use Livewire\Component;
use Illuminate\Validation\Rule;

class EditKategori extends Component
{
    public $kategoriId;
    public $nama;

    public function mount(Kategori $kategori)
    {
        $this->kategoriId = $kategori->id;
        $this->nama = $kategori->nama;
    }

    protected function rules()
    {
        return [
            'nama' => ['required', 'string', 'min:3', Rule::unique('kategoris')->ignore($this->kategoriId)],
        ];
    }

    public function update()
    {
        $this->validate();

        $kategori = Kategori::findOrFail($this->kategoriId);
        $kategori->update([
            'nama' => $this->nama,
        ]);

        session()->flash('success', 'Kategori berhasil diperbarui!');
        return redirect()->route('kategori.index');
    }

    public function render()
    {
        return view('livewire.kategori.edit-kategori')
            ->layout('layouts.app');
    }
}
