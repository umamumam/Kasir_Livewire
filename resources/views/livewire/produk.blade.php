<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📦 Manajemen Produk</h1>

    <div class="flex flex-col sm:flex-row justify-between items-center mb-5 gap-3">
        {{-- Tombol untuk beralih tampilan --}}
        <div class="flex gap-2">
            <button wire:click="showAllProducts"
                class="px-4 py-2 rounded-lg shadow-md transition
                {{ !$isLowStockMode ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                Semua Produk
            </button>
            <button wire:click="showLowStock"
                class="px-4 py-2 rounded-lg shadow-md transition
                {{ $isLowStockMode ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                Stok Rendah
            </button>
        </div>

        @if(!$isLowStockMode)
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="🔍 Cari produk..."
            class="p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-1/3 text-sm">

        <button wire:click="create"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 flex items-center space-x-2 transition">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512">
                <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7
                0-32 14.3-32 32s14.3 32 32 32H192V432c0
                17.7 14.3 32 32 32s32-14.3
                32-32V288H400c17.7 0 32-14.3
                32-32s-14.3-32-32-32H256V80z" />
            </svg>
            <span>Tambah Produk</span>
        </button>
        @endif
    </div>

    {{-- Tabel Daftar Produk --}}
    <div class="bg-white shadow-lg overflow-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">No</th>

                    {{-- Nama Produk (Kolom yang bisa di-klik untuk sorting) --}}
                    <th wire:click="sortBy('nama')"
                        class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer transition-colors duration-200 hover:text-indigo-600">
                        <div class="flex items-center space-x-1">
                            <span>Nama Produk</span>
                            {{-- Ikon panah --}}
                            @if ($sortField === 'nama')
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                            </svg>
                            @endif
                        </div>
                    </th>

                    <th class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Harga</th>

                    {{-- Stok (Kolom yang bisa di-klik untuk sorting) --}}
                    <th wire:click="sortBy('stok')"
                        class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer transition-colors duration-200 hover:text-indigo-600">
                        <div class="flex items-center space-x-1">
                            <span>Stok</span>
                            {{-- Ikon panah --}}
                            @if ($sortField === 'stok')
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                            </svg>
                            @endif
                        </div>
                    </th>

                    <th class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                    <th class="px-5 py-3 border-b text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produks as $index => $produk)
                <tr wire:key="{{ $produk->id }}" class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 border-b text-sm text-gray-700">
                        {{ $produks->firstItem() + $index }}
                    </td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">
                        {{ $produk->nama }}
                    </td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">
                        {{ number_format($produk->harga_jual) }}
                    </td>
                    <td
                        class="px-5 py-4 border-b text-sm @if($produk->stok <= 20) text-red-500 font-bold @else text-gray-700 @endif">
                        {{ $produk->stok }}
                    </td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">
                        {{ $produk->kategori->nama }}
                    </td>
                    <td class="px-5 py-4 border-b text-sm text-center">
                        <div class="flex justify-center gap-2">
                            {{-- Tombol Edit --}}
                            <button wire:click="edit({{ $produk->id }})"
                                class="px-3 py-2 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 flex items-center gap-1 text-sm shadow-sm transition">
                                <i class="fas fa-edit"></i>
                                {{-- <span>Edit</span> --}}
                            </button>

                            {{-- Tombol Hapus - panggil fungsi JS --}}
                            <button onclick="confirmDelete({{ $produk->id }})"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center gap-1 text-sm shadow-sm transition">
                                <i class="fas fa-trash-alt"></i>
                                {{-- <span>Hapus</span> --}}
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-6 text-center text-gray-500 text-sm">
                        Tidak ada data produk yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Link Pagination --}}
    <div class="px-5 py-4 mt-4">
        {{ $produks->links() }}
    </div>

    {{-- Modal untuk Tambah/Edit Produk --}}
    @if($showModal)
    <div class="fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center z-50">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                {{ $isCreating ? 'Tambah Produk Baru' : 'Edit Produk' }}
            </h3>
            <form wire:submit.prevent="{{ $isCreating ? 'store' : 'update' }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input wire:model.defer="nama" type="text" id="nama"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="kode" class="block text-sm font-medium text-gray-700">Kode Produk</label>
                        <input wire:model.defer="kode" type="text" id="kode"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('kode') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="harga_beli" class="block text-sm font-medium text-gray-700">Harga Beli</label>
                        <input wire:model.defer="harga_beli" type="number" id="harga_beli"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('harga_beli') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="harga_jual" class="block text-sm font-medium text-gray-700">Harga Jual</label>
                        <input wire:model.defer="harga_jual" type="number" id="harga_jual"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('harga_jual') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input wire:model.defer="stok" type="number" id="stok"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('stok') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select wire:model.defer="kategori_id" id="kategori_id"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-4 py-2 bg-gray-200 rounded-lg text-gray-800 hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

{{-- Script SweetAlert2 --}}
<script>
    document.addEventListener('livewire:initialized', () => {

        // Menampilkan notifikasi sukses
        @this.on('swal:success', (event) => {
            const { title, text, icon } = event[0];
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
            });
        });

        // Fungsi untuk konfirmasi hapus
        window.confirmDelete = (produkId) => {
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Anda tidak akan bisa mengembalikan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('delete', produkId);
                }
            });
        }
    });
</script>
