<?php
session_start();
include '../koneksi.php';

// AKTIVASI ADMIN LANGSUNG DI FILE INI
if (isset($_GET['aktifkan_id'])) {
    $id = $_GET['aktifkan_id'];
    mysqli_query($conn, "UPDATE admin SET level = 'on' WHERE id = '$id'");
    header("location: t_admin.php");
    exit();
}

// DEAKTIVASI ADMIN LANGSUNG DI FILE INI
if (isset($_POST['deactivate_id'])) {
    $id = $_POST['deactivate_id'];
    mysqli_query($conn, "UPDATE admin SET level = 'off' WHERE id = '$id'");
    header("location: t_admin.php");
    exit();
}

// Pagination setup
$limit = 5; // Maks 5 data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Get current logged-in admin ID to exclude from list
$current_admin_id = $_SESSION['admin_id'] ?? 0;

// Query to get all admins except the currently logged-in one
$result = mysqli_query($conn, "SELECT * FROM admin WHERE id != $current_admin_id ORDER BY id DESC LIMIT $start, $limit");
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM admin WHERE id != $current_admin_id");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_page = ceil($total_data / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    .pagination .page-item.active .page-link {
      background-color: #007bff;
      border-color: #007bff;
    }
  </style>
</head>
<body>

<div class="container table-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Data Admin</h4>
    <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
    <a href="regis.php" class="btn btn-success">+ Tambah Admin</a>
  </div>

  <table class="table table-striped table-hover text-center">
    <thead>
      <tr>
        <th>No</th>
        <th>Email</th>
        <th>Username</th>
        <th>Password</th>
        <th>Status Level</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = $start + 1;
      while ($row = mysqli_fetch_assoc($result)) {
        if ($row['level'] == 'on') {
            $badge = "<span class='badge bg-success'>Aktif</span>";
            $actionButton = "
            <form action='t_admin.php' method='POST' style='display:inline;'>
                <input type='hidden' name='deactivate_id' value='{$row['id']}'>
                <button type='submit' class='btn btn-sm btn-danger'>Nonaktif</button>
            </form>";
        } else {
            $badge = "<span class='badge bg-danger'>Nonaktif</span>";
            $actionButton = "
            <a href='?aktifkan_id={$row['id']}' class='btn btn-sm btn-warning'>Aktifkan</a>";
        }

        echo "<tr>
                <td>$no</td>
                <td>{$row['email']}</td>
                <td>{$row['username']}</td>
                <td>{$row['password']}</td>
                <td>{$badge}</td>
                <td>{$actionButton}</td>
              </tr>";
        $no++;
      }

      if (mysqli_num_rows($result) == 0) {
        echo "<tr><td colspan='6'>Tidak ada data admin.</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <nav>
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_page; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $total_page): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<script>
  setTimeout(() => {
    console.log("Menjalankan auto-delete admin level off...");

    fetch('delete.php')
      .then(response => response.text())
      .then(data => {
        console.log("Respon dari server:", data);
        // reload hanya jika ada respon "berhasil"
        if (data.includes("berhasil")) {
          location.reload();
        }
      })
      .catch(error => console.error("Gagal menjalankan auto-delete:", error));
  }, 5 * 1000); // 10 detik
</script>

</body>
</html>
