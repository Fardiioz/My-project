<?php
session_start();
include 'koneksi.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Define categories for flashcards
$categories = [
    'Kuliner',
    'Pakaian Adat',
    'Senjata Tradisional',
    'Tarian Adat',
    'Alat Musik Tradisional',
    'Upacara Adat',
    'Lagu Tradisional'
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Kategori</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        .flashcard-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }
        .flashcard {
            background: #667eea;
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
            flex: 1 1 200px;
            text-align: center;
            font-weight: 600;
            cursor: default;
            user-select: none;
            transition: background 0.3s ease;
        }
        .flashcard:hover {
            background: #764ba2;
            box-shadow: 0 6px 15px rgba(118, 75, 162, 0.6);
        }
    </style>
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
        <h2>Kelola Kategori</h2>
        <p>Berikut adalah kategori dalam bentuk flashcard:</p>
        <div class="flashcard-container">
            <?php foreach ($categories as $category): ?>
                <div class="flashcard">
                    <h3><?php echo htmlspecialchars($category); ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
        <p>Fitur pengelolaan kategori akan dikembangkan lebih lanjut.</p>
    </div>

    <script src="script.js"></script>
</body>
</html>
