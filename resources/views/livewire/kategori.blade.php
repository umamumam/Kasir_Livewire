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
                    <h5 class="card-title fw-semibold mb-0">Manajemen Kategori</h5>
                    <a href="{{ route('kategori.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>Tambah Kategori
                    </a>
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
                                <th style="width: 80px;">NO</th>
                                <th>NAMA KATEGORI</th>
                                <th class="text-center" style="width: 150px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategoris as $index => $kategori)
                            <tr wire:key="{{ $kategori->id }}">
                                <td>{{ $kategoris->firstItem() + $index }}</td>
                                <td>{{ $kategori->nama }}</td>
                                <td class="text-center">
                                    <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $kategori->id }})" type="button" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    Tidak ada data kategori yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($kategoris->hasPages())
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $kategoris->firstItem() }} sampai {{ $kategoris->lastItem() }} dari {{ $kategoris->total() }} entri
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            @if($kategoris->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">Sebelumnya</span></li>
                            @else
                                <li class="page-item"><a wire:click="previousPage" class="page-link" href="javascript:void(0)">Sebelumnya</a></li>
                            @endif

                            @php
                                $currentPage = $kategoris->currentPage();
                                $lastPage = $kategoris->lastPage();
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

                            @if($kategoris->hasMorePages())
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

    window.confirmDelete = (kategoriId) => {
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
                $wire.call('delete', kategoriId);
            }
        });
    };
</script>
@endscript
