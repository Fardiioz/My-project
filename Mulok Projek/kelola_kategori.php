<?php
session_start();
include 'koneksi.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$username = $_SESSION['username'] ?? 'Admin';

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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      left: -250px;
      top: 0;
      width: 250px;
      height: 100%;
      background: #2c3e50;
      color: white;
      transition: left 0.3s ease;
      z-index: 1000;
    }
    .sidebar.active {
      left: 0;
    }
    .sidebar h2 {
      text-align: center;
      padding: 1rem;
      background: #1a252f;
      margin: 0;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      padding: 15px 20px;
      border-bottom: 1px solid #34495e;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .sidebar ul li:hover {
      background: #34495e;
      cursor: pointer;
    }

    /* Menu Toggle */
    .menu-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      font-size: 24px;
      color: #2c3e50;
      cursor: pointer;
      z-index: 1100;
    }

    /* Content */
    .content {
      padding: 2rem;
      margin-left: 0;
      transition: margin-left 0.3s ease;
    }
    .sidebar.active ~ .content {
      margin-left: 250px;
    }

    /* Card Container */
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
      transition: background 0.3s ease, box-shadow 0.3s ease;
    }
    .flashcard:hover {
      background: #764ba2;
      box-shadow: 0 6px 15px rgba(118, 75, 162, 0.6);
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.1);
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>

  <!-- Menu Toggle -->
  <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2>Menu</h2>
    <ul>
      <li><a href="Dashboard_admin.php"><i class="fas fa-home"></i> Beranda</a></li>
      <li><a href="kelola_admin.php"><i class="fas fa-user-shield"></i> Kelola Admin</a></li>
      <li><a href="kelola_kategori.php"><i class="fas fa-list"></i> Kelola Kategori</a></li>
      <li><a href="Dashboard_admin.php?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <!-- Content -->
  <div class="content">
    

    <div class="card">
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
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }
  </script>

</body>
</html>
