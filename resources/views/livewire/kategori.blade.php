<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                    <div class="mb-3 mb-sm-0">
                        <h5 class="card-title fw-semibold">
                            <i class="ti ti-category me-2"></i>Manajemen Kategori
                        </h5>
                    </div>
                </div>

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
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="ti ti-search"></i>
                            </span>
                            <input wire:model.live.debounce.300ms="search" type="text" 
                                class="form-control border-start-0" placeholder="Cari kategori..." data-testid="search-input">
                        </div>
                        <button wire:click="create" class="btn btn-primary d-flex align-items-center gap-2" data-testid="btn-add-category">
                            <i class="ti ti-plus"></i>
                            <span>Tambah Kategori</span>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover text-nowrap mb-0 align-middle" data-testid="categories-table">
                        <thead class="text-dark fs-4">
                            <tr>
                                <th class="border-bottom-0" style="width: 80px;">
                                    <h6 class="fw-semibold mb-0">No</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">Nama Kategori</h6>
                                </th>
                                <th class="border-bottom-0 text-center" style="width: 150px;">
                                    <h6 class="fw-semibold mb-0">Aksi</h6>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategoris as $index => $kategori)
                            <tr wire:key="{{ $kategori->id }}" data-testid="category-row-{{ $kategori->id }}">
                                <td class="border-bottom-0">
                                    <span class="fw-normal">{{ $kategoris->firstItem() + $index }}</span>
                                </td>
                                <td class="border-bottom-0">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="round-40 d-flex align-items-center justify-content-center bg-light-primary rounded-circle">
                                            <i class="ti ti-folder text-primary fs-5"></i>
                                        </span>
                                        <h6 class="fw-semibold mb-0">{{ $kategori->nama }}</h6>
                                    </div>
                                </td>
                                <td class="border-bottom-0 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button wire:click="edit({{ $kategori->id }})"
                                            class="btn btn-sm btn-warning d-flex align-items-center justify-content-center" 
                                            style="width: 32px; height: 32px;" 
                                            title="Edit" data-testid="btn-edit-{{ $kategori->id }}">
                                            <i class="ti ti-edit fs-5"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $kategori->id }})"
                                            class="btn btn-sm btn-danger d-flex align-items-center justify-content-center" 
                                            style="width: 32px; height: 32px;" 
                                            title="Hapus" data-testid="btn-delete-{{ $kategori->id }}">
                                            <i class="ti ti-trash fs-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ti ti-folder-off fs-1 text-muted mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada data kategori yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($kategoris->hasPages())
                <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 gap-3">
                    <div class="text-muted fs-3">
                        Menampilkan {{ $kategoris->firstItem() }} sampai {{ $kategoris->lastItem() }} dari {{ $kategoris->total() }} data
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0" data-testid="pagination">
                            {{-- Previous --}}
                            @if($kategoris->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a wire:click="previousPage" class="page-link" href="javascript:void(0)">Previous</a>
                                </li>
                            @endif

                            {{-- Page Numbers --}}
                            @foreach($kategoris->getUrlRange(1, $kategoris->lastPage()) as $page => $url)
                                @if($page == $kategoris->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a wire:click="gotoPage({{ $page }})" class="page-link" href="javascript:void(0)">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if($kategoris->hasMorePages())
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

{{-- Modal untuk Tambah/Edit Kategori --}}
@if($showModal)
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" data-testid="category-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">
                    <i class="ti ti-{{ $isCreating ? 'plus' : 'edit' }} me-2"></i>
                    {{ $isCreating ? 'Tambah Kategori Baru' : 'Edit Kategori' }}
                </h5>
                <button type="button" wire:click="$set('showModal', false)" class="btn-close" data-testid="btn-close-modal"></button>
            </div>
            <form wire:submit.prevent="{{ $isCreating ? 'store' : 'update' }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Kategori</label>
                        <input wire:model.defer="nama" type="text" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama kategori" data-testid="input-nama">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

        window.confirmDelete = (kategoriId) => {
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
                    @this.call('delete', kategoriId);
                }
            });
        }
    });
</script>
