<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                    <div class="mb-3 mb-sm-0">
                        <h5 class="card-title fw-semibold">
                            <i class="ti ti-package me-2"></i>Manajemen Produk
                        </h5>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button wire:click="showAllProducts"
                            class="btn {{ !$isLowStockMode ? 'btn-primary' : 'btn-light' }}" data-testid="btn-all-products">
                            Semua Produk
                        </button>
                        <button wire:click="showLowStock"
                            class="btn {{ $isLowStockMode ? 'btn-primary' : 'btn-light' }}" data-testid="btn-low-stock">
                            <i class="ti ti-alert-triangle me-1"></i>Stok Rendah
                        </button>
                    </div>
                </div>

                @if(!$isLowStockMode)
                <div class="d-flex flex-wrap gap-3 mb-4 align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label mb-0">Show</label>
                        <select wire:model.live="perPage" class="form-select form-select-sm" style="width: auto;" data-testid="per-page-select">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="text-muted">entries</span>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <input wire:model.live.debounce.300ms="search" type="text" 
                            class="form-control" style="width: 200px;" placeholder="Cari produk..." data-testid="search-input">
                        <button wire:click="create" class="btn btn-primary d-flex align-items-center gap-2" data-testid="btn-add-product">
                            <i class="ti ti-plus"></i>
                            <span>Tambah Produk</span>
                        </button>
                    </div>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap mb-0 align-middle" data-testid="products-table">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0" style="width: 60px;">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th wire:click="sortBy('nama')" class="border-bottom-0" style="cursor: pointer;" data-testid="sort-nama">
                                    <h6 class="fw-semibold mb-0 d-flex align-items-center gap-1">
                                        Nama Produk
                                        @if ($sortField === 'nama')
                                            <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                        @endif
                                    </h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Harga</h6>
                                </th>
                                <th wire:click="sortBy('stok')" class="border-bottom-0" style="cursor: pointer;" data-testid="sort-stok">
                                    <h6 class="fw-semibold mb-0 d-flex align-items-center gap-1">
                                        Stok
                                        @if ($sortField === 'stok')
                                            <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                        @endif
                                    </h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Kategori</h6>
                                </th>
                                <th class="border-bottom-0 text-center" style="width: 120px;">
                                    <h6 class="fw-semibold mb-0">Aksi</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produks as $index => $produk)
                            <tr wire:key="{{ $produk->id }}" data-testid="product-row-{{ $produk->id }}">
                                <td class="border-bottom-0">
                                    <span class="fw-normal">{{ $produks->firstItem() + $index }}</span>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $produk->nama }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <span class="fw-normal">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</span>
                                </td>
                                <td class="border-bottom-0">
                                    @if($produk->stok <= 20)
                                        <span class="badge bg-danger rounded-3 fw-semibold">{{ $produk->stok }}</span>
                                    @elseif($produk->stok <= 50)
                                        <span class="badge bg-warning rounded-3 fw-semibold">{{ $produk->stok }}</span>
                                    @else
                                        <span class="badge bg-success rounded-3 fw-semibold">{{ $produk->stok }}</span>
                                    @endif
                                </td>
                                <td class="border-bottom-0">
                                    <span class="badge bg-light-primary text-primary rounded-3">{{ $produk->kategori->nama }}</span>
                                </td>
                                <td class="border-bottom-0 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button wire:click="edit({{ $produk->id }})"
                                            class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" 
                                            style="width: 32px; height: 32px;" 
                                            title="Edit" data-testid="btn-edit-{{ $produk->id }}">
                                            <i class="ti ti-edit fs-5"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $produk->id }})"
                                            class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" 
                                            style="width: 32px; height: 32px;" 
                                            title="Hapus" data-testid="btn-delete-{{ $produk->id }}">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ti ti-package-off fs-1 text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada data produk yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($produks->hasPages())
                <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 gap-3">
                    <div class="text-muted fs-3">
                        Menampilkan {{ $produks->firstItem() }} sampai {{ $produks->lastItem() }} dari {{ $produks->total() }} data
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0" data-testid="pagination">
                            {{-- Previous --}}
                            @if($produks->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a wire:click="previousPage" class="page-link" href="javascript:void(0)">Previous</a>
                                </li>
                            @endif

                            {{-- Page Numbers (limited) --}}
                            @php
                                $currentPage = $produks->currentPage();
                                $lastPage = $produks->lastPage();
                                $start = max(1, $currentPage - 2);
                                $end = min($lastPage, $currentPage + 2);
                            @endphp

                            @if($start > 1)
                                <li class="page-item">
                                    <a wire:click="gotoPage(1)" class="page-link" href="javascript:void(0)">1</a>
                                </li>
                                @if($start > 2)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                            @endif

                            @for($page = $start; $page <= $end; $page++)
                                @if($page == $currentPage)
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a wire:click="gotoPage({{ $page }})" class="page-link" href="javascript:void(0)">{{ $page }}</a>
                                    </li>
                                @endif
                            @endfor

                            @if($end < $lastPage)
                                @if($end < $lastPage - 1)
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                @endif
                                <li class="page-item">
                                    <a wire:click="gotoPage({{ $lastPage }})" class="page-link" href="javascript:void(0)">{{ $lastPage }}</a>
                                </li>
                            @endif

                            {{-- Next --}}
                            @if($produks->hasMorePages())
                                <li class="page-item">
                                    <a wire:click="nextPage" class="page-link" href="javascript:void(0)">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal untuk Tambah/Edit Produk --}}
@if($showModal)
<div class="modal-backdrop fade show"></div>
<div class="modal fade show" tabindex="-1" style="display: block;" data-testid="product-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    <i class="ti ti-{{ $isCreating ? 'plus' : 'edit' }} me-2"></i>
                    {{ $isCreating ? 'Tambah Produk Baru' : 'Edit Produk' }}
                </h5>
                <button type="button" wire:click="$set('showModal', false)" class="btn-close" data-testid="btn-close-modal"></button>
            </div>
            <form wire:submit.prevent="{{ $isCreating ? 'store' : 'update' }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label fw-semibold">Nama Produk</label>
                            <input wire:model="nama" type="text" id="nama" class="form-control @error('nama') is-invalid @enderror" data-testid="input-nama">
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label fw-semibold">Kode Produk</label>
                            <input wire:model="kode" type="text" id="kode" class="form-control @error('kode') is-invalid @enderror" data-testid="input-kode">
                            @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga_beli" class="form-label fw-semibold">Harga Beli</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input wire:model="harga_beli" type="number" id="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" data-testid="input-harga-beli">
                            </div>
                            @error('harga_beli') <div class="text-danger fs-2 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga_jual" class="form-label fw-semibold">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input wire:model="harga_jual" type="number" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" data-testid="input-harga-jual">
                            </div>
                            @error('harga_jual') <div class="text-danger fs-2 mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label fw-semibold">Stok</label>
                            <input wire:model="stok" type="number" id="stok" class="form-control @error('stok') is-invalid @enderror" data-testid="input-stok">
                            @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
                            <select wire:model="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" data-testid="select-kategori">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="$set('showModal', false)" class="btn btn-light" data-testid="btn-cancel">Batal</button>
                    <button type="submit" class="btn btn-primary" data-testid="btn-save">
                        <i class="ti ti-device-floppy me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Script SweetAlert2 --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('swal:success', (event) => {
            const { title, text, icon } = event[0];
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
            });
        });

        window.confirmDelete = (produkId) => {
            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Anda tidak akan bisa mengembalikan ini!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5D87FF",
                cancelButtonColor: "#FA896B",
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
