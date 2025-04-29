<?php 
require_once 'config.php';
//Mengimpor konfigurasi koneksi database
include 'header.php'; 
// Menyisipkan bagian header halaman
?>      

<!-- Memuat Bootstrap Icons untuk penggunaan ikon visual di seluruh halaman -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Judul halaman dengan ikon Bootstrap untuk tampilan yang menarik -->
<h2 class="mb-4"><i class="bi bi-box-seam-fill me-2"></i>Daftar Produk</h2>

<!-- Form untuk membungkus tabel dan tombol aksi terkait produk -->
<form id="productsForm">
    <!-- Tombol aksi untuk menghapus produk yang dipilih -->
    <div class="mb-3 d-flex flex-wrap gap-2">
        <button type="button" id="deleteSelected" class="btn btn-danger">
            <i class="bi bi-trash-fill me-1"></i> Hapus Terpilih
        </button>
    </div>

    <!-- Membuat tabel yang responsif untuk daftar produk -->
    <div class="table-responsive">
        <table id="productsTable" class="table table-striped table-bordered table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <!-- Checkbox untuk memilih semua baris -->
                    <th width="50"><input type="checkbox" id="selectAll"></th>
                    <th><i class="bi bi-hash"></i></th>
                    <th><i class="bi bi-box"></i> Nama Barang</th>
                    <th><i class="bi bi-card-text"></i> Deskripsi</th>
                    <th><i class="bi bi-tags-fill"></i> Tipe</th>
                    <th><i class="bi bi-123"></i> Stock</th>
                    <th><i class="bi bi-cash-stack"></i> Harga</th>
                    <th><i class="bi bi-image"></i> Gambar</th>
                    <th><i class="bi bi-gear-fill"></i> Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mengambil semua data produk dari database, urut dari yang terbaru
                $sql = "SELECT * FROM products ORDER BY id DESC";
                $result = $conn->query($sql);
                $no = 1;

                // Menampilkan data produk ke dalam baris-baris tabel
                while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <!-- Checkbox untuk memilih produk tertentu -->
                    <td><input type="checkbox" class="product-checkbox" value="<?= $row['id'] ?>"></td>
                    
                    <!-- Nomor urut -->
                    <td><?= $no++ ?></td>
                    
                    <!-- Nama barang -->
                    <td><?= $row['nama_barang'] ?></td>
                    <!-- Deskripsi produk, dibatasi maksimal 50 karakter -->
                    <td><?= substr($row['description'], 0, 50) . (strlen($row['description']) > 50 ? '...' : '') ?></td>
                    
                    <!-- Tipe produk -->
                    <td><?= $row['tipe'] ?></td>
                    
                    <!-- Jumlah stock tersedia -->
                    <td><?= $row['stock'] ?></td>
                    
                    <!-- Harga produk dalam format rupiah -->
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    
                    <!-- Menampilkan gambar jika tersedia, atau teks jika tidak ada -->
                    <td>
                        <?php if ($row['gambar']) : ?>
                            <img src="assets/images/<?= $row['gambar'] ?>" alt="<?= $row['nama_barang'] ?>" class="img-fluid rounded" width="50">
                        <?php else : ?>
                            <i class="bi bi-file-image text-muted"></i> No Image
                        <?php endif; ?>
                    </td>
                    
                    <!-- Tombol aksi untuk edit dan hapus -->
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <!-- Tombol edit akan membuka halaman update -->
                            <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <!-- Tombol hapus dengan konfirmasi sebelum aksi dilakukan -->
                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="bi bi-trash-fill"></i> Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</form>
<?php require_once 'footer.php'; ?> 
