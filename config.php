<?php
// Konfigurasi koneksi ke database MySQL
$host = "mysql.railway.internal"; // Host dan port MySQL
$user = "root"; // Username untuk mengakses MySQL
$pass = "LoPfboGPzQMVbrMJsCWByXOEjbaLLssl"; // Password untuk mengakses MySQL (kosongkan jika tidak ada)
$dbname = "railway"; // Nama database yang akan digunakan

// Aktifkan pelaporan kesalahan agar mudah mendeteksi error pada query MySQL
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Membuat koneksi ke MySQL
$conn = new mysqli($host, $user, $pass);

// Memeriksa apakah koneksi berhasil, jika gagal hentikan eksekusi dan tampilkan pesan kesalahan
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Mengatur karakter encoding menjadi utf8mb4 untuk mendukung karakter spesial dan emoji
$conn->set_charset("utf8mb4");

// Perintah SQL untuk membuat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

// Mengeksekusi query untuk membuat database, dan memeriksa apakah berhasil
if ($conn->query($sql)) {
  // Memilih database yang telah dibuat atau sudah ada
  if (!$conn->select_db($dbname)) {
    die("Gagal memilih database: " . $conn->error);
  }

  // Perintah SQL untuk membuat tabel 'products' jika belum ada
  $sql = "CREATE TABLE IF NOT EXISTS products (
        id INT(11) AUTO_INCREMENT PRIMARY KEY, 
        nama_barang VARCHAR(255) NOT NULL,      
        description TEXT, 
        harga DECIMAL(12,2) NOT NULL,
        tipe ENUM('perabotan', 'Laptop', 'Hp') NOT NULL, 
        stock INT(11) NOT NULL, 
        gambar VARCHAR(255),      
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"; // Tentukan engine InnoDB dan karakter set utf8mb4

  // Mengeksekusi query untuk membuat tabel dan memeriksa apakah berhasil
  if (!$conn->query($sql)) {
    die("Gagal membuat tabel: " . $conn->error);
  }
} else {
  // Jika gagal membuat database, tampilkan pesan kesalahan
  die("Gagal membuat database: " . $conn->error);
}

// Fungsi untuk membersihkan input pengguna agar aman dari injeksi dan karakter berbahaya
function sanitize($data)
{
  global $conn;
  // Escape karakter khusus untuk SQL, hapus tag HTML, dan konversi karakter ke entitas HTML
  return htmlspecialchars(strip_tags($conn->real_escape_string($data)));
} ?>
