<?php
// koneksi.php — konfigurasi koneksi database (development)
$host = "localhost";
$user = "root";
$password = "";
$database = "kalimantan_mulok";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    // Jangan tampilkan detail sensitif ke user di produksi
    error_log("DB connect error: " . $conn->connect_error);
    die("Koneksi database gagal.");
}

// set charset agar aman untuk emoji / encoding
$conn->set_charset("utf8mb4");
?>
