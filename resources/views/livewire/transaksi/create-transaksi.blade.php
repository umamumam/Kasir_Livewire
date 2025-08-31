<div class="relative py-3">
    <div
        class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-light-blue-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
    </div>
    <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
        <h1 class="text-3xl font-extrabold text-center text-gray-900 mb-8">Kasir Transaksi Baru 🛒</h1>

        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Cari Produk</h2>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="searchProduk"
                    placeholder="Cari produk berdasarkan nama atau kode..."
                    class="form-input w-full rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition duration-150 ease-in-out">
                @if($showProdukList)
                <ul
                    class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-2 shadow-xl max-h-60 overflow-y-auto">
                    @forelse($searchResults as $produk)
                    <li wire:key="search-{{ $produk->id }}" wire:click="addProdukToCart({{ $produk->id }})"
                        class="p-4 cursor-pointer hover:bg-gray-100 transition duration-150 ease-in-out border-b border-gray-200 last:border-b-0">
                        <p class="font-medium text-gray-900">{{ $produk->nama }} <span class="text-xs text-gray-500">({{
                                $produk->kode }})</span></p>
                        <p class="text-sm text-indigo-600">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
                    </li>
                    @empty
                    <li class="p-4 text-gray-500 italic text-sm">Produk tidak ditemukan.</li>
                    @endforelse
                </ul>
                @endif
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Keranjang Belanja</h2>
            @if(count($cartItems) > 0)
            <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cartItems as $index => $item)
                            <tr wire:key="cart-{{ $item['produk_id'] }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{
                                    $item['nama_produk'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{
                                    number_format($item['harga'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" wire:change="updateJumlah({{ $index }}, $event.target.value)"
                                        value="{{ $item['jumlah'] }}" min="1"
                                        class="w-20 form-input rounded-md text-center">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{
                                    number_format($item['subtotal'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="removeFromCart({{ $index }})"
                                        class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out"
                                        title="Hapus dari keranjang">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="text-center py-8 bg-gray-50 rounded-lg shadow-inner">
                <p class="text-gray-500 italic">Keranjang masih kosong. Silakan tambahkan produk.</p>
            </div>
            @endif
        </div>

        <div class="bg-gray-100 p-6 rounded-lg shadow-inner">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Ringkasan Pembayaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                    <input type="date" wire:model="tanggaltransaksi"
                        class="form-input w-full rounded-md mt-1 bg-gray-200 cursor-not-allowed" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Belanja</label>
                    <input type="text" value="Rp {{ number_format($total, 0, ',', '.') }}"
                        class="form-input w-full rounded-md mt-1 bg-indigo-50 text-indigo-700 font-bold text-lg"
                        disabled>
                </div>
                <div>
                    <label for="bayar" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                    <input type="number" id="bayar" wire:model.live="bayar" class="form-input w-full rounded-md mt-1">
                    @error('bayar') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kembalian</label>
                    <input type="text" value="Rp {{ number_format($kembalian, 0, ',', '.') }}"
                        class="form-input w-full rounded-md mt-1 bg-green-50 text-green-700 font-bold text-lg" disabled>
                </div>
            </div>

            @error('cartItems')
            <p class="text-red-500 text-sm mt-4 text-center">Keranjang belanja tidak boleh kosong.</p>
            @enderror

            <div class="mt-8">
                <button wire:click="store" wire:loading.attr="disabled" wire:target="store"
                    class="w-full py-3 px-4 border border-transparent rounded-full shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition duration-150 ease-in-out transform hover:scale-105">
                    <span wire:loading.remove wire:target="store">Simpan Transaksi</span>
                    <span wire:loading wire:target="store">Menyimpan...</span>
                </button>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                    @this.on('swal:success', (data) => {
                        Swal.fire({
                            title: data.title,
                            text: data.text,
                            icon: data.icon,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        });
                    });

                    @this.on('swal:error', (data) => {
                        Swal.fire({
                            title: data.title,
                            text: data.text,
                            icon: data.icon,
                        });
                    });
                });
        </script>
        @endpush
    </div>
</div>
@include('livewire.partials._sweetalert-script')
