<?php
session_start();
include 'koneksi.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Ambil kategori dari database
$categories = [];
$result = $conn->query("SELECT nama_kategori FROM kategori ORDER BY nama_kategori ASC");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['nama_kategori'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Kategori</title>
    <link rel="stylesheet" href="styles.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #83a4d4, #b6fbff);
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #2c3e50;
            padding: 1rem;
            color: white;
        }
        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-menu {
            list-style: none;
            display: flex;
            gap: 1rem;
            margin: 0;
            padding: 0;
        }
        .navbar-menu li a {
            color: white;
            text-decoration: none;
        }
        .navbar-menu li a:hover {
            text-decoration: underline;
        }
        .container {
            padding: 2rem;
        }
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
                <li><a href="logout_admin.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2>Kelola Kategori</h2>
        <p>Berikut adalah kategori dalam bentuk flashcard:</p>
        <div class="flashcard-container">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="flashcard">
                        <h3><?php echo htmlspecialchars($category); ?></h3>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p><em>Tidak ada kategori tersedia.</em></p>
            <?php endif; ?>
        </div>
        <p>Fitur pengelolaan kategori akan dikembangkan lebih lanjut.</p>
    </div>

    <script src="script.js"></script>
</body>
</html>
