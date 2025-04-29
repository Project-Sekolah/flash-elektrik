</main>

<!-- Footer halaman, ditampilkan di bagian bawah dengan latar gelap dan teks putih -->
<footer class="bg-dark text-white p-3 mt-4">
    <div class="container text-center">
        <!-- Teks hak cipta yang menampilkan tahun saat ini secara dinamis menggunakan PHP -->
        <p>&copy; <?php echo date('Y'); ?> Flash Electric - All Rights Reserved</p>
    </div>
</footer>

<!-- Memuat jQuery, dibutuhkan untuk sebagian besar plugin JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Memuat bundle JavaScript Bootstrap termasuk Popper.js untuk komponen interaktif -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Plugin DataTables utama untuk membuat tabel menjadi interaktif -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Integrasi DataTables dengan Bootstrap 5 untuk tampilan yang konsisten -->
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Menjalankan kode setelah seluruh dokumen siap
    $(document).ready(function() {
        // Inisialisasi DataTable pada elemen dengan ID 'productsTable'
        $('#productsTable').DataTable({
            scrollX: true,         // Mengaktifkan scroll horizontal untuk tabel lebar
            autoWidth: false,      // Menonaktifkan auto width agar lebar kolom bisa dikontrol manual
            language: {
                search: "_INPUT_", // Menyesuaikan elemen input pencarian
                searchPlaceholder: "Cari produk..." // Placeholder untuk pencarian
            }
        });

        // Event saat checkbox 'selectAll' diklik
        $('#selectAll').click(function() {
            // Semua checkbox produk akan mengikuti status 'selectAll'
            $('.product-checkbox').prop('checked', this.checked);
        });

        // Event saat tombol 'deleteSelected' diklik
        $('#deleteSelected').click(function() {
            let selected = [];
            
            // Mengumpulkan ID produk yang dicentang
            $('.product-checkbox:checked').each(function() {
                selected.push($(this).val());
            });

            // Validasi: cek jika ada produk yang dipilih
            if (selected.length > 0) {
                // Konfirmasi penghapusan
                if (confirm('Apakah Anda yakin ingin menghapus data terpilih?')) {
                    // Redirect ke halaman delete dengan parameter ID yang dipilih
                    window.location.href = 'delete.php?ids=' + selected.join(',');
                }
            } else {
                // Pesan jika tidak ada yang dipilih
                alert('Pilih setidaknya satu data untuk dihapus');
            }
        });
    });
</script>

</body>
</html>
