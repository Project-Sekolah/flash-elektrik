<?php require_once 'config.php';
//Memuat file konfigurasi yang berisi koneksi database dan fungsi bantu
// Hapus satu produk jika parameter 'id' tersedia di URL
if (isset($_GET['id'])) {
    $id = sanitize($_GET['id']); // Sanitasi ID dari parameter URL
    $sql = "DELETE FROM products WHERE id = $id"; // Query untuk menghapus satu produk
    $conn->query($sql); // Eksekusi query
} 
// Hapus beberapa produk jika parameter 'ids' tersedia
elseif (isset($_GET['ids'])) {
  //contoh nya nih data awalnya giji
   //$_GET['ids'] = "34,55,66" 
    $ids = explode(',', $_GET['ids']); // Pisahkan string ID menjadi array
    /*setelah di pisah jadi gini
   $ids = ["34", "55", "66"]; */
    $ids = array_map('intval', $ids); // Ubah setiap nilai menjadi integer untuk keamanan
    //setelah dj ubah jadi int
     /* $ids = [34, 55, 66]; */
    $ids = implode(',', $ids); // Gabungkan kembali menjadi string ID yang valid untuk SQL
    //setelah di gabung
    //$idsn = "34,55,66";
    

    // Ambil nama file gambar dari produk yang akan dihapus
    $sql = "SELECT gambar FROM products WHERE id IN ($ids) AND gambar IS NOT NULL";
    $result = $conn->query($sql);
    
    // Hapus file gambar dari direktori jika file tersebut ada
    while ($row = $result->fetch_assoc()) {
        if ($row['gambar'] && file_exists("assets/images/" . $row['gambar'])) {
          
            unlink("assets/images/" . $row['gambar']);
        }
    }

    // Hapus data produk dari database
    $sql = "DELETE FROM products WHERE id IN ($ids)";
    $conn->query($sql);
}

// Setelah proses selesai, arahkan kembali ke halaman dashboard
header('Location: dashboard.php');
exit();
?>
