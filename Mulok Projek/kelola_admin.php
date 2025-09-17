<?php
session_start();
include 'koneksi.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <h1>Dashboard Admin</h1>
            <ul class="navbar-menu">
                <li><a href="Dashboard_admin.php">Beranda</a></li>
                <li><a href="kelola_admin.php">Kelola Admin</a></li>
                <li><a href="kelola_kategori.php">Kelola Kategori</a></li>
                <li><a href="Dashboard_admin.php?logout=true">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Kelola Admin</h2>
        <p>Di sini Anda dapat menambah, mengedit, atau menghapus admin lainnya.</p>
        <!-- Placeholder for future functionality -->
        <p>Fitur ini akan dikembangkan lebih lanjut.</p>
    </div>

    <script src="script.js"></script>
</body>
</html>
