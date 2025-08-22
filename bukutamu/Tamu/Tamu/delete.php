<?php
// Memulai sesi
session_start();

// Memeriksa apakah username ada dalam session, jika tidak, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit(); // Menghentikan eksekusi script setelah redirect
}

// Menginclude file koneksi ke database
include '../koneksi.php';

// Memeriksa apakah ID tamu ada dalam query string
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Mengambil ID tamu dan mengkonversi ke integer

    // Query untuk menghapus tamu berdasarkan ID
    $sql = "DELETE FROM tamu WHERE id = $id";

    // Menjalankan query
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, redirect kembali ke halaman daftar tamu
        header("Location: tamu.php?message=Data berhasil dihapus");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika ID tidak ada, redirect kembali ke halaman daftar tamu
    header("Location: tamu.php?message=ID tidak valid");
    exit();
}
?>