<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'koneksi.php';

// Pesan feedback
$message = "";

// Handle toggle status
if (isset($_POST['toggle_status'])) {
    $admin_id = (int) $_POST['admin_id'];

    // ambil status sekarang
    $check = mysqli_query($conn, "SELECT status FROM admin WHERE id = $admin_id");
    $row = mysqli_fetch_assoc($check);
    $newStatus = ($row['status'] === 'on') ? 'off' : 'on';

    $query = "UPDATE admin SET status = '$newStatus' WHERE id = $admin_id";
    if (mysqli_query($conn, $query)) {
        $message = "Status admin berhasil diubah.";
    } else {
        $message = "Gagal mengubah status admin.";
    }
}

// Ambil semua admin
$admins_query = "SELECT * FROM admin";
$admins_result = mysqli_query($conn, $admins_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(to right, #83a4d4, #b6fbff);
      padding: 30px;
      font-family: Arial, sans-serif;
    }
    .table-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    table thead {
      background: #007bff;
      color: white;
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
    .sidebar ul li { padding: 15px 20px; border-bottom: 1px solid #34495e; }
    .sidebar ul li a { color: white; text-decoration: none; display: flex; align-items: center; }
    .sidebar ul li:hover { background: #34495e; cursor: pointer; }
    .menu-toggle {
      position: fixed; top: 15px; left: 15px; font-size: 24px;
      color: #73945bff; cursor: pointer; z-index: 1100;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Kelola Admin</h2>
    <?php if ($message): ?>
      <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <p>Di sini Anda dapat menambah, mengedit, atau menonaktifkan admin lainnya.</p>
  </div>
    
  <div class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <div class="sidebar" id="sidebar">
    <h2>Menu</h2>
    <ul>
      <li><a href="Dashboard_admin.php">Beranda</a></li>
      <li><a href="kelola_admin.php">Kelola Admin</a></li>
      <li><a href="kelola_kategori.php">Kelola Kategori</a></li>
      <li><a href="Dashboard_admin.php?logout=true">Logout</a></li>
    </ul>
  </div>

  <div class="container table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Data Admin</h4>
      <a href="tambah.php" class="btn btn-success">+ Tambah Admin</a>
    </div>

    <table class="table table-striped table-hover text-center">
      <thead>
        <tr>
          <th>No</th>
          <th>Email</th>
          <th>Password</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($admins_result)) {
          echo "<tr>
                  <td>$no</td>
                  <td>{$row['email']}</td>
                  <td>{$row['password']}</td>
                  <td>
                    <form method='POST' style='display:inline;'>
                      <input type='hidden' name='admin_id' value='{$row['id']}'>";
          
          $btnClass = $row['status'] === 'on' ? 'btn-success' : 'btn-danger';
          $btnText  = $row['status'] === 'on' ? 'Aktif' : 'Nonaktif';

          echo "<button type='submit' name='toggle_status' class='btn btn-sm $btnClass'>$btnText</button>
                </form>
              </td>
            </tr>";
          $no++;
        }

        if (mysqli_num_rows($admins_result) == 0) {
          echo "<tr><td colspan='4'>Tidak ada data admin.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("active");
    }
  </script>
</body>
</html>
