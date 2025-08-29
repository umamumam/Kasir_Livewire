<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">📝 Manajemen Transaksi</h1>
    {{-- Search & Tambah Transaksi --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-5 gap-3">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="🔍 Cari transaksi..."
            class="p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-1/3 text-sm">

        <a href="{{ route('transaksi.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 flex items-center space-x-2 transition">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512">
                <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
            </svg>
            <span>Tambah Transaksi</span>
        </a>
    </div>

    {{-- Tabel Daftar Transaksi --}}
    <div class="bg-white shadow-lg overflow-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">No</th>
                    <th wire:click="sortBy('kode')"
                        class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer">Kode Transaksi @if($sortField === 'kode') @include('livewire.partials._sort-icon', ['direction' => $sortDirection]) @endif</th>
                    <th wire:click="sortBy('tanggaltransaksi')"
                        class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer">Tanggal @if($sortField === 'tanggaltransaksi') @include('livewire.partials._sort-icon', ['direction' => $sortDirection]) @endif</th>
                    <th wire:click="sortBy('total')"
                        class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase cursor-pointer">Total @if($sortField === 'total') @include('livewire.partials._sort-icon', ['direction' => $sortDirection]) @endif</th>
                    <th class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Bayar</th>
                    <th class="px-5 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Kembalian</th>
                    <th class="px-5 py-3 border-b text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksis as $index => $transaksi)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 border-b text-sm text-gray-700">{{ $transaksis->firstItem() + $index }}</td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">{{ $transaksi->kode }}</td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">{{ \Carbon\Carbon::parse($transaksi->tanggaltransaksi)->translatedFormat('d F Y') }}</td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 border-b text-sm text-gray-700">Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
                    <td class="px-5 py-4 border-b text-sm text-center">
                        <div class="flex justify-center gap-2">
                            <button wire:click="showDetail({{ $transaksi->id }})" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center gap-1 text-sm shadow-sm transition">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="px-3 py-2 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 flex items-center gap-1 text-sm shadow-sm transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $transaksi->id }})" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center gap-1 text-sm shadow-sm transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-6 text-center text-gray-500 text-sm">Tidak ada data transaksi yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Link Pagination --}}
    <div class="px-5 py-4 mt-4">
        {{ $transaksis->links() }}
    </div>

    {{-- Modal Detail Transaksi --}}
    @if($showDetailModal && $selectedTransaksi)
        <div class="fixed inset-0 bg-gray-700 bg-opacity-50 flex items-center justify-center z-50">
            <div class="w-full max-w-2xl bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">Detail Transaksi {{ $selectedTransaksi->kode }}</h3>
                <div class="mb-4">
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($selectedTransaksi->tanggaltransaksi)->translatedFormat('d F Y H:i:s') }}</p>
                    <p><strong>Total:</strong> Rp {{ number_format($selectedTransaksi->total, 0, ',', '.') }}</p>
                    <p><strong>Bayar:</strong> Rp {{ number_format($selectedTransaksi->bayar, 0, ',', '.') }}</p>
                    <p><strong>Kembalian:</strong> Rp {{ number_format($selectedTransaksi->kembalian, 0, ',', '.') }}</p>
                </div>
                <h4 class="font-bold text-gray-700 mb-2">Item Transaksi:</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($selectedTransaksi->detailTransaksis as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->produk->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-now-2rap text-sm text-gray-500">{{ $detail->jumlah }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" wire:click="closeDetailModal"
                        class="px-4 py-2 bg-gray-200 rounded-lg text-gray-800 hover:bg-gray-300 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Script SweetAlert2 --}}
@include('livewire.partials._sweetalert-script')
