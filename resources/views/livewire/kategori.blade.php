<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📂 Manajemen Kategori</h1>

    <div class="flex flex-col sm:flex-row justify-between items-center mb-5 gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="🔍 Cari kategori..."
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
            <span>Tambah Kategori</span>
        </button>
    </div>

    {{-- Tabel Daftar Kategori --}}
    <div class="bg-white shadow-lg rounded-xl overflow-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                    <th class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Nama Kategori
                    </th>
                    <th class="px-5 py-3 border-b text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategoris as $index => $kategori)
                <tr wire:key="{{ $kategori->id }}" class="hover:bg-gray-50 transition">
                {{-- <tr class="hover:bg-gray-50 transition"> --}}
                    <td class="px-5 py-4 border-b text-sm text-gray-700">
                        {{ $kategoris->firstItem() + $index }}
                    </td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">
                        {{ $kategori->nama }}
                    </td>
                    <td class="px-5 py-4 border-b text-sm text-center">
                        <div class="flex justify-center gap-2">
                            {{-- Tombol Edit --}}
                            <button wire:click="edit({{ $kategori->id }})"
                                class="px-3 py-2 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 flex items-center gap-1 text-sm shadow-sm transition">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </button>

                            {{-- Tombol Hapus - panggil fungsi JS --}}
                            <button onclick="confirmDelete({{ $kategori->id }})"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center gap-1 text-sm shadow-sm transition">
                                <i class="fas fa-trash-alt"></i>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-5 py-6 text-center text-gray-500 text-sm">
                        Tidak ada data kategori yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Link Pagination --}}
    <div class="px-5 py-4 mt-4">
        {{ $kategoris->links() }}
    </div>

    {{-- Modal untuk Tambah/Edit Kategori --}}
    @if($showModal)
    <div class="fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center z-50">
        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">
                {{ $isCreating ? 'Tambah Kategori Baru' : 'Edit Kategori' }}
            </h3>
            <form wire:submit.prevent="{{ $isCreating ? 'store' : 'update' }}">
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                    <input wire:model.defer="nama" type="text" id="nama"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
            const { title, text, icon } = event[0]; // Perbaikan di sini
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
            });
        });

        // Fungsi untuk konfirmasi hapus
        window.confirmDelete = (kategoriId) => {
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
                    @this.call('delete', kategoriId);
                }
            });
        }
    });
</script>
