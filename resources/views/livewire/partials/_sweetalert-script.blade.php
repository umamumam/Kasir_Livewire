<script>
    document.addEventListener('livewire:initialized', () => {
        // Menampilkan notifikasi sukses dan melakukan redirect jika ada
        @this.on('swal:success', (event) => {
            const data = event[0]; // Ambil data dari event
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

        // Menampilkan notifikasi gagal
        @this.on('swal:error', (event) => {
            const data = event[0]; // Ambil data dari event
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
            });
        });

        // Fungsi untuk konfirmasi hapus
        window.confirmDelete = (transaksiId) => {
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
                    @this.call('delete', transaksiId);
                }
            });
        }
    });
</script>
