<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="card-title fw-semibold mb-0">Tambah Kategori Baru</h5>
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Kembali
                    </a>
                </div>

                <form wire:submit.prevent="store">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama kategori">
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>Simpan
                        </button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
