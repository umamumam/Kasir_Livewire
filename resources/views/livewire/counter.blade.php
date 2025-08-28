<div class="card text-center">
    <div class="card-body">
        <h1 class="mb-3">{{ $count }}</h1>
        <p class="text-muted">hahaha</p>

        <div class="d-flex justify-content-center gap-2">
            <button wire:click="increment" class="btn btn-success">
                <i class="bi bi-plus"></i> Tambah
            </button>

            <button wire:click="decrement" class="btn btn-danger">
                <i class="bi bi-dash"></i> Kurang
            </button>
        </div>
    </div>
</div>
