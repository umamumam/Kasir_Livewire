<?php

namespace App\Livewire;

use App\Models\Kategori;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class KategoriController extends Component
{
    use WithPagination;

    // Properti publik untuk menyimpan data form
    public $kategoriId;
    public $nama;

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
    protected $rules = [
        'nama' => 'required|string|min:3|unique:kategoris,nama',
    ];

    // Reset properti
    public function resetForm()
    {
        $this->kategoriId = null;
        $this->nama = '';
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

    // Buka modal untuk membuat kategori baru
    public function create()
    {
        $this->resetForm();
        $this->isCreating = true;
        $this->isEditing = false;
        $this->showModal = true;
    }

    // Simpan kategori baru atau yang diedit
    public function store()
    {
        $validatedData = $this->validate();
        Kategori::create($validatedData);

        $this->showModal = false;
        $this->resetForm();
        // Mengirim event SweetAlert sukses
        $this->dispatch('swal:success', [
            'title' => 'Berhasil!',
            'text' => 'Kategori berhasil ditambahkan.',
            'icon' => 'success'
        ]);
    }

    // Buka modal untuk mengedit kategori
    public function edit(Kategori $kategori)
    {
        $this->resetForm();
        $this->isEditing = true;
        $this->isCreating = false;
        $this->showModal = true;
        $this->kategoriId = $kategori->id;
        $this->nama = $kategori->nama;

        // Aturan validasi unik diperbarui saat edit
        $this->rules = [
            'nama' => ['required', 'string', 'min:3', Rule::unique('kategoris')->ignore($this->kategoriId)],
        ];
    }

    // Perbarui kategori
    public function update()
    {
        $this->validate();
        $kategori = Kategori::findOrFail($this->kategoriId);
        $kategori->update([
            'nama' => $this->nama,
        ]);

        $this->showModal = false;
        $this->resetForm();
        // Mengirim event SweetAlert sukses
        $this->dispatch('swal:success', [
            'title' => 'Berhasil!',
            'text' => 'Kategori berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    // Hapus kategori
    public function delete($id)
    {
        Kategori::find($id)->delete();
        // Mengirim event SweetAlert sukses setelah penghapusan
        $this->dispatch('swal:success', [
            'title' => 'Terhapus!',
            'text' => 'Kategori berhasil dihapus.',
            'icon' => 'success'
        ]);
    }

    // Render tampilan
    public function render()
    {
        $kategoris = Kategori::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.kategori', compact('kategoris'))->layout('layouts.app');
    }
}
