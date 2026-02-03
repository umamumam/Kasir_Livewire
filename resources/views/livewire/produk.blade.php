<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Flash Message --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- Header dengan judul dan tombol tambah --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="card-title fw-semibold mb-0">Manajemen Produk</h5>
                    <div class="d-flex gap-2">
                        <button wire:click="showAllProducts" type="button"
                            class="btn {{ !$isLowStockMode ? 'btn-primary' : 'btn-outline-primary' }}">
                            Semua Produk
                        </button>
                        <button wire:click="showLowStock" type="button"
                            class="btn {{ $isLowStockMode ? 'btn-danger' : 'btn-outline-danger' }}">
                            Stok Rendah
                        </button>
                        @if(!$isLowStockMode)
                        <a href="{{ route('produk.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Tambah Produk
                        </a>
                        @endif
                    </div>
                </div>

                @if(!$isLowStockMode)
                {{-- Filter row --}}
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span>Tampilkan</span>
                        <select wire:model.live="perPage" class="form-select form-select-sm" style="width: 70px;">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span>entri</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span>Cari:</span>
                        <input wire:model.live.debounce.300ms="search" type="text" class="form-control form-control-sm" style="width: 200px;">
                    </div>
                </div>
                @endif

                {{-- Tabel --}}
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">NO</th>
                                <th wire:click="sortBy('nama')" style="cursor: pointer;">
                                    NAMA PRODUK
                                    @if ($sortField === 'nama')
                                        <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th>HARGA</th>
                                <th wire:click="sortBy('stok')" style="cursor: pointer;">
                                    STOK
                                    @if ($sortField === 'stok')
                                        <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th>KATEGORI</th>
                                <th class="text-center" style="width: 120px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produks as $index => $produk)
                            <tr wire:key="{{ $produk->id }}">
                                <td>{{ $produks->firstItem() + $index }}</td>
                                <td>{{ $produk->nama }}</td>
                                <td>Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                <td>
                                    @if($produk->stok <= 20)
                                        <span class="badge bg-danger">{{ $produk->stok }}</span>
                                    @elseif($produk->stok <= 50)
                                        <span class="badge bg-warning">{{ $produk->stok }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $produk->stok }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light-primary text-primary">{{ $produk->kategori->nama }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $produk->id }})" type="button" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Tidak ada data produk yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($produks->hasPages())
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $produks->firstItem() }} sampai {{ $produks->lastItem() }} dari {{ $produks->total() }} entri
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            @if($produks->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">Sebelumnya</span></li>
                            @else
                                <li class="page-item"><a wire:click="previousPage" class="page-link" href="javascript:void(0)">Sebelumnya</a></li>
                            @endif

                            @php
                                $currentPage = $produks->currentPage();
                                $lastPage = $produks->lastPage();
                                $start = max(1, $currentPage - 2);
                                $end = min($lastPage, $currentPage + 2);
                            @endphp

                            @if($start > 1)
                                <li class="page-item"><a wire:click="gotoPage(1)" class="page-link" href="javascript:void(0)">1</a></li>
                                @if($start > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            @for($page = $start; $page <= $end; $page++)
                                <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                                    @if($page == $currentPage)
                                        <span class="page-link">{{ $page }}</span>
                                    @else
                                        <a wire:click="gotoPage({{ $page }})" class="page-link" href="javascript:void(0)">{{ $page }}</a>
                                    @endif
                                </li>
                            @endfor

                            @if($end < $lastPage)
                                @if($end < $lastPage - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item"><a wire:click="gotoPage({{ $lastPage }})" class="page-link" href="javascript:void(0)">{{ $lastPage }}</a></li>
                            @endif

                            @if($produks->hasMorePages())
                                <li class="page-item"><a wire:click="nextPage" class="page-link" href="javascript:void(0)">Selanjutnya</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">Selanjutnya</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('swal:success', (event) => {
        const { title, text, icon } = event[0];
        Swal.fire({ title, text, icon });
    });

    window.confirmDelete = (produkId) => {
        Swal.fire({
            title: "Apakah Anda Yakin?",
            text: "Data tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5D87FF",
            cancelButtonColor: "#FA896B",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.call('delete', produkId);
            }
        });
    };
</script>
@endscript
