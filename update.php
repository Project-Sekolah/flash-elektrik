<?php require_once 'config.php';
//Memuat konfigurasi koneksi database dan fungsi bantu
// Cek apakah parameter ID tersedia, jika tidak maka kembali ke dashboard
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$id = sanitize($_GET['id']); // Sanitasi ID dari URL

// Jika form disubmit melalui metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan sanitasi data input dari form
    $nama_barang = sanitize($_POST['nama_barang']);
    $description = sanitize($_POST['description']);
    $harga = sanitize($_POST['harga']);
    $tipe = sanitize($_POST['tipe']);
    $stock = sanitize($_POST['stock']);
    
    // Proses upload gambar (jika ada gambar baru)
    $gambar = $_POST['existing_gambar']; // Default gunakan gambar lama
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "assets/images/";
        
        // Buat direktori jika belum ada
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Validasi ekstensi file yang diperbolehkan 
        $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        // Jika file valid, simpan dengan nama baru yang unik
        if (in_array($file_ext, $allowed_ext)) {
            $new_filename = uniqid() . '.' . $file_ext;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                // Hapus gambar lama jika ada
                if ($gambar && file_exists($target_dir . $gambar)) {
                    unlink($target_dir . $gambar);
                }
                $gambar = $new_filename; // Set gambar baru
            }
        }
    }

    // Query untuk memperbarui data produk berdasarkan ID
    $sql = "UPDATE products SET 
            nama_barang = '$nama_barang', 
            description = '$description', 
            harga = '$harga', 
            tipe = '$tipe', 
            stock = '$stock', 
            gambar = '$gambar' 
            WHERE id = $id";

    // Jalankan query dan alihkan ke dashboard jika berhasil
    if ($conn->query($sql)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Error: " . $conn->error; // Simpan pesan error jika gagal
    }
}

// Ambil data produk berdasarkan ID untuk ditampilkan dalam form
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// Jika produk tidak ditemukan, kembali ke dashboard
if (!$product) {
    header('Location: dashboard.php');
    exit();
}
?>

<?php include 'header.php'; ?> <!-- Menyisipkan bagian header -->

<!-- Judul halaman -->
<h2 class="mb-4">Edit Produk</h2>

<!-- Tampilkan pesan error jika ada -->
<?php if (isset($error)) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<!-- Form untuk mengedit produk -->
<form method="POST" enctype="multipart/form-data">
    <!-- Input nama produk -->
    <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang</label>
        <input type="text" class="form-control" id="nama_barang" name="nama_barang" value="<?= $product['nama_barang'] ?>" required>
    </div>

    <!-- Input deskripsi produk -->
    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="description" name="description" rows="3" required><?= $product['description'] ?></textarea>
    </div>

    <!-- Input harga produk -->
    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" class="form-control" id="harga" name="harga" value="<?= $product['harga'] ?>" required>
    </div>

    <!-- Dropdown tipe produk -->
    <div class="mb-3">
        <label for="tipe" class="form-label">Tipe</label>
        <select class="form-select" id="tipe" name="tipe" required>
            <option value="perabotan" <?= $product['tipe'] == 'perabotan' ? 'selected' : '' ?>>Perabotan</option>
            <option value="Laptop" <?= $product['tipe'] == 'Laptop' ? 'selected' : '' ?>>Laptop</option>
            <option value="Hp" <?= $product['tipe'] == 'Hp' ? 'selected' : '' ?>>Hp</option>
        </select>
    </div>

    <!-- Input stok produk -->
    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" value="<?= $product['stock'] ?>" required>
    </div>

    <!-- Upload dan tampilkan gambar produk -->
    <div class="mb-3">
        <label for="gambar" class="form-label">Gambar</label>
        <input type="file" class="form-control" id="gambar" name="gambar">
        <input type="hidden" name="existing_gambar" value="<?= $product['gambar'] ?>">

        <?php if ($product['gambar']) : ?>
            <div class="mt-2">
                <img src="assets/images/<?= $product['gambar'] ?>" alt="Current Image" width="100" class="img-thumbnail">
                <p class="text-muted">Gambar saat ini</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tombol aksi -->
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="dashboard.php" class="btn btn-secondary">Batal</a>
</form>

<?php include 'footer.php'; ?> <!-- Menyisipkan bagian footer -->
