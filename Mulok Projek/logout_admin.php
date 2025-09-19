<?php
session_start();
include 'koneksi.php';

// Tidak perlu update login_log karena tabelnya tidak ada

// Hapus semua session
session_unset();
session_destroy();

// Arahkan ke halaman login
header("Location: admin_login.php");
exit();
?>
