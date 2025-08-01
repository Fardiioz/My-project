<?php
include '../koneksi.php';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Ambil data admin dengan limit
$result = mysqli_query($conn, "SELECT * FROM admin LIMIT $start, $limit");
if (!$result) {
  die("Gagal ambil data admin: " . mysqli_error($conn));
}

// Hitung total data
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM admin");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_page = ceil($total_data / $limit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #ffffff, #6dd5fa);
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
      margin: 0;
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
    }

    .sidebar ul li a i {
      margin-right: 10px;
      width: 20px;
    }

    .sidebar ul li:hover {
      background: #34495e;
      cursor: pointer;
    }

    .menu-toggle {
      position: fixed;
      top: 15px;
      left: 6px;
      font-size: 24px;
      color: #2980b9;
      cursor: pointer;
      z-index: 1100;
    }

    .content {
      padding: 2rem;
      margin-left: 0;
      transition: margin-left 0.3s ease;
    }

    .sidebar.active ~ .content {
      margin-left: 250px;
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

<!-- Sidebar Toggle -->
<div class="menu-toggle" onclick="toggleSidebar()">
  <i class="fas fa-bars"></i>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <h2>Menu</h2>
  <ul>
    <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
    <li><a href="#"><i class="fas fa-users"></i> Tamu</a></li>
    <li><a href="t_admin.php"><i class="fas fa-user-shield"></i> Admin</a></li>
    <li><a href="#"><i class="fas fa-user-check"></i> Kehadiran</a></li>
    <li><a href="#"><i class="fas fa-file-alt"></i> Laporan</a></li>
    <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="content">
  <div class="container table-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Data Admin</h4>
      <div>
        <a href="create_admin.php" class="btn btn-success me-2">+ Tambah Admin</a>
        <!-- Auto-Delete Level Kosong button removed -->
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>No</th>
          <th>Username</th>
          <th>Email</th>
          <th>Level</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = $start + 1;
        mysqli_data_seek($result, 0); // pastikan pointer awal data
        while ($row = mysqli_fetch_assoc($result)) :
        ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['level'] ?></td>
            <td>
              <a href='edit_admin.php?id=<?= $row['id'] ?>' class='btn btn-sm btn-warning'>Edit</a>
              <!-- Delete button removed -->
              <?php if ($row['level'] == 'on'): ?>
                <a href='toggle_level.php?id=<?= $row['id'] ?>&level=off' class='btn btn-sm btn-outline-secondary'>Nonaktifkan</a>
              <?php else: ?>
                <a href='toggle_level.php?id=<?= $row['id'] ?>&level=on' class='btn btn-sm btn-outline-success'>Aktifkan</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <nav>
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a></li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_page; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
        <?php if ($page < $total_page): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</div>

<!-- Sidebar Script -->
<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
  }

  // Optional: Auto-delete admin dengan level kosong
  // Auto-delete script removed
  setTimeout(() => {
    fetch('delete.php')
      .then(res => res.text())
      .then(data => {
        if (data.includes("berhasil")) {
          location.reload();
        }
      }).catch(err => console.error("Gagal auto-delete:", err));
  }, 10000); // 10 detik
  */
</script>

</body>
</html>
