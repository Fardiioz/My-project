<?php
session_start();
include "koneksi.php"; // koneksi ke MySQL

// Cek login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_SESSION['admin_id'];
$email = $_SESSION['admin_email']; // ambil email dari session

// proses update profile
if (isset($_POST['update_profile'])) {
    $new_email = $_POST['email'];
    $new_password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($new_password) {
        $stmt = $conn->prepare("UPDATE admin SET email=?, password=? WHERE id=?");
        $stmt->bind_param("ssi", $new_email, $new_password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE admin SET email=? WHERE id=?");
        $stmt->bind_param("si", $new_email, $id);
    }
    $stmt->execute();

    $_SESSION['admin_email'] = $new_email; // update session
    header("Location: dashboard_admin.php?page=profile&success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin - Kebudayaan Kalimantan</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f3f3f3;
    }

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

    .sidebar.active { left: 0; }

    .sidebar h2 {
      text-align: center;
      padding: 1rem;
      background: #1a252f;
      margin: 0;
    }

    .sidebar ul { list-style: none; padding: 0; }

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

    .sidebar ul li:hover { background: #34495e; cursor: pointer; }

    .menu-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      font-size: 24px;
      color: #2c3e50;
      cursor: pointer;
      z-index: 1100;
    }

    .content {
      padding: 2rem;
      margin-left: 0;
      transition: margin-left 0.3s ease;
    }

    .sidebar.active ~ .content { margin-left: 250px; }

    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 2px 10px 10px rgba(0, 0, 0, 0.1);
    }

    .form-group { margin-bottom: 15px; }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      background: #3498db;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover { background: #2980b9; }

    .alert {
      padding: 10px;
      background: #2ecc71;
      color: white;
      border-radius: 5px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

  <!-- Tombol toggle -->
  <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="?page=home"><i class="fas fa-home"></i> Home</a></li>
      <li><a href="?page=admin"><i class="fas fa-user-shield"></i> Admin</a></li>
      <li><a href="?page=kategori"><i class="fas fa-list"></i> Kategori</a></li>
      <li><a href="?page=budaya"><i class="fas fa-leaf"></i> Budaya</a></li>
      <li><a href="?page=profile"><i class="fas fa-user"></i> Profile</a></li>
      <li><a href="logout_admin.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <!-- Konten -->
  <div class="content">
    <div class="card">
      <?php
        if(isset($_GET['page'])){
          switch($_GET['page']){
            case 'home':
              echo "<h1>Selamat Datang di Dashboard Kebudayaan Kalimantan</h1>
                    <p>Halo, <b>" . htmlspecialchars($email) . "</b></p>
                    <p>Pilih menu di sidebar untuk mulai mengelola data.</p>";
              break;

            case 'admin':
              header("Location: kelola_admin.php");
              exit();

            case 'kategori':
              header("Location: kelola_kategori.php");
              exit();

            case 'budaya':
              header("Location: riwayat_budaya.php");
              exit();

            case 'profile':
              $stmt = $conn->prepare("SELECT email, status FROM admin WHERE id=?");
              $stmt->bind_param("i", $id);
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();

              if(isset($_GET['success'])){
                echo "<div class='alert'>Profil berhasil diperbarui!</div>";
              }

              echo "<h2>Profil Admin</h2>
                    <form method='post'>
                      <div class='form-group'>
                        <label>Email:</label>
                        <input type='email' name='email' value='".htmlspecialchars($row['email'])."' required>
                      </div>
                      <div class='form-group'>
                        <label>Password Baru (kosongkan jika tidak diganti):</label>
                        <input type='password' name='password'>
                      </div>
                      <div class='form-group'>
                        <label>Status:</label>
                        <input type='text' value='".htmlspecialchars($row['status'])."' readonly>
                      </div>
                      <button type='submit' name='update_profile'>Simpan Perubahan</button>
                    </form>";
              break;

            default:
              echo "<h2>Halaman tidak ditemukan</h2>";
          }
        } else {
          echo "<h1>Selamat Datang di Dashboard Kebudayaan Kalimantan</h1>
                <p>Halo, <b>" . htmlspecialchars($email) . "</b></p>
                <p>Pilih menu di sidebar untuk mulai mengelola data.</p>";
        }
      ?>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }
  </script>

</body>
</html>
