<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{-- Header dengan judul dan tombol --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="card-title fw-semibold mb-0">Manajemen Transaksi</h5>
                    <div class="d-flex gap-2">
                        <button wire:click="$set('filterMode', 'daily')" type="button"
                            class="btn {{ $filterMode === 'daily' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Hari Ini
                        </button>
                        <button wire:click="$set('filterMode', 'all')" type="button"
                            class="btn {{ $filterMode === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Semua
                        </button>
                        <a href="{{ route('transaksi.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Tambah Transaksi
                        </a>
                    </div>
                </div>

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

                {{-- Tabel --}}
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">NO</th>
                                <th wire:click="sortBy('kode')" style="cursor: pointer;">
                                    KODE TRANSAKSI
                                    @if($sortField === 'kode')
                                        <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('tanggaltransaksi')" style="cursor: pointer;">
                                    TANGGAL
                                    @if($sortField === 'tanggaltransaksi')
                                        <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('total')" style="cursor: pointer;">
                                    TOTAL
                                    @if($sortField === 'total')
                                        <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th>BAYAR</th>
                                <th>KEMBALIAN</th>
                                <th class="text-center" style="width: 180px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksis as $index => $transaksi)
                            <tr wire:key="{{ $transaksi->id }}">
                                <td>{{ $transaksis->firstItem() + $index }}</td>
                                <td>{{ $transaksi->kode }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggaltransaksi)->translatedFormat('d M Y') }}</td>
                                <td>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <button wire:click="showDetail({{ $transaksi->id }})" type="button" class="btn btn-sm btn-info" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                    <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <a href="{{ route('transaksi.nota', $transaksi->id) }}" target="_blank" class="btn btn-sm btn-success" title="Print">
                                        <i class="ti ti-printer"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $transaksi->id }})" type="button" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Tidak ada data transaksi yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($transaksis->hasPages())
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $transaksis->firstItem() }} sampai {{ $transaksis->lastItem() }} dari {{ $transaksis->total() }} entri
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            @if($transaksis->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">Sebelumnya</span></li>
                            @else
                                <li class="page-item"><a wire:click="previousPage" class="page-link" href="javascript:void(0)">Sebelumnya</a></li>
                            @endif

                            @php
                                $currentPage = $transaksis->currentPage();
                                $lastPage = $transaksis->lastPage();
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

                            @if($transaksis->hasMorePages())
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

{{-- Modal Detail Transaksi --}}
@if($showDetailModal && $selectedTransaksi)
<div class="modal-backdrop fade show"></div>
<div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi {{ $selectedTransaksi->kode }}</h5>
                <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Tanggal:</strong></p>
                        <p>{{ \Carbon\Carbon::parse($selectedTransaksi->tanggaltransaksi)->translatedFormat('d F Y H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Kode:</strong></p>
                        <p>{{ $selectedTransaksi->kode }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Total:</strong></p>
                        <p class="text-primary fw-bold">Rp {{ number_format($selectedTransaksi->total, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Bayar:</strong></p>
                        <p>Rp {{ number_format($selectedTransaksi->bayar, 0, ',', '.') }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1"><strong>Kembalian:</strong></p>
                        <p>Rp {{ number_format($selectedTransaksi->kembalian, 0, ',', '.') }}</p>
                    </div>
                </div>

                <h6 class="fw-semibold mb-3">Item Transaksi:</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>PRODUK</th>
                                <th>HARGA</th>
                                <th>JUMLAH</th>
                                <th>SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedTransaksi->detailTransaksis as $detail)
                            <tr>
                                <td>{{ $detail->produk->nama }}</td>
                                <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

@script
<script>
    $wire.on('swal:success', (event) => {
        const { title, text, icon } = event[0];
        Swal.fire({ title, text, icon });
    });

    window.confirmDelete = (transaksiId) => {
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
                $wire.call('delete', transaksiId);
            }
        });
    };
</script>
@endscript
