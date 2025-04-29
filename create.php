<?php require_once 'config.php';
//Memuat konfigurasi koneksi database dan fungsi bantu 
// Mengecek apakah form telah disubmit menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil dan membersihkan data input dari form
    $nama_barang = sanitize($_POST['nama_barang']);
    $description = sanitize($_POST['description']);
    $harga = sanitize($_POST['harga']);
    $tipe = sanitize($_POST['tipe']);
    $stock = sanitize($_POST['stock']);
    
    // Proses upload gambar jika ada
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "assets/images/";
        
        // Membuat folder target jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        // Mendapatkan ekstensi file dan memvalidasi apakah termasuk ekstensi yang diperbolehkan 
        $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Jika file valid, proses simpan dengan nama acak agar unik
      
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid() . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;
           
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                $gambar = $new_filename;
            }
        }
    }
    
    // Menyusun query untuk menyimpan data produk ke dalam database
    $sql = "INSERT INTO products (nama_barang, description, harga, tipe, stock, gambar) 
            VALUES ('$nama_barang', '$description', '$harga', '$tipe', '$stock', '$gambar')";
    // Menjalankan query dan redirect ke dashboard jika berhasil
    if ($conn->query($sql)) {
        header('Location: dashboard.php');
        exit();
    } else {
        // Menyimpan pesan error jika gagal menyimpan ke database
        $error = "Error: " . $conn->error;
    }
} 
?>
<?php include 'header.php';
//Menyisipkan bagian header halaman
?> 

<!-- Judul halaman -->
<h2 class="mb-4">Tambah Produk Baru</h2>

<!-- Menampilkan pesan error jika ada -->
<?php if (isset($error)) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<!-- Form input produk baru -->
<form method="POST" enctype="multipart/form-data">
    <!-- Input nama produk -->
    <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang</label>
        <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
    </div>
    
    <!-- Input deskripsi produk -->
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>
    
    <!-- Input harga produk -->
    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" class="form-control" id="harga" name="harga" required>
    </div>
    
    <!-- Dropdown pilihan tipe produk -->
    <div class="mb-3">
        <label for="tipe" class="form-label">Tipe</label>
        <select class="form-select" id="tipe" name="tipe" required>
            <option value="">Pilih Tipe</option>
            <option value="perabotan">Perabotan</option>
            <option value="Laptop">Laptop</option>
            <option value="Hp">Hp</option>
        </select>
    </div>
    
    <!-- Input stok produk -->
    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" required>
    </div>
    
    <!-- Upload gambar produk -->
    <div class="mb-3">
        <label for="gambar" class="form-label">Gambar</label>
        <input type="file" class="form-control" id="gambar" name="gambar">
    </div>
    
    <!-- Tombol simpan dan batal -->
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="dashboard.php" class="btn btn-secondary">Batal</a>
</form>
<?php require_once 'footer.php'; ?> <!-- Menyisipkan bagian footer halaman -->
