<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit();
}

include '../koneksi.php';
$username = $_SESSION['username'];

// Ambil input filter tanggal
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

// Pagination
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$mulai = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// Filter query
$filter_sql = "";
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $filter_sql = " WHERE tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

// Query data tamu
$sql = "SELECT * FROM tamu $filter_sql ORDER BY tanggal DESC LIMIT $mulai, $batas";
$data = mysqli_query($conn, $sql);

// Hitung total data
$total_data = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tamu $filter_sql"));
$total_halaman = ceil($total_data / $batas);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tamu - Buku Tamu Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --text-color: #2d3748;
            --light-bg: #f7fafc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: var(--text-color);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }

        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
            border: none;
        }

        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #e2e8f0;
        }

        .table tbody tr:hover {
            background-color: #f7fafc;
        }

        .pagination {
            margin-top: 2rem;
        }

        .page-link {
            border-radius: 10px;
            margin: 0 0.25rem;
            color: var(--primary-color);
            border: 1px solid #e2e8f0;
        }

        .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #718096;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #cbd5e0;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 1rem;
        }

        .stats-card h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 1rem;
                padding: 0;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .table-responsive {
                border-radius: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book-open"></i> Buku Tamu Digital
            </a>
            <div class="navbar-nav ms-auto">
                <a href="../admin/dashboard.php" class="btn back-btn">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="main-container">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-users mb-2"></i>
                    <h3><?= number_format($total_data) ?></h3>
                    <p>Total Tamu</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-calendar-day mb-2"></i>
                    <h3><?= date('d M Y') ?></h3>
                    <p>Tanggal Hari Ini</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <i class="fas fa-clock mb-2"></i>
                    <h3><?= date('H:i') ?></h3>
                    <p>Waktu Sekarang</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter"></i> Filter Data Tamu
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" name="tanggal_awal" 
                                   value="<?= htmlspecialchars($tanggal_awal) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="tanggal_akhir" 
                                   value="<?= htmlspecialchars($tanggal_akhir) ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="tamu.php" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-table"></i> Daftar Tamu
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Instansi</th>
                                <th>Keperluan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = $mulai + 1;
                            while ($row = mysqli_fetch_assoc($data)) {
                                echo "<tr>
                                    <td>{$no}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['instansi']}</td>
                                    <td>{$row['keperluan']}</td>
                                    <td>{$row['tanggal']}</td>
                                    <td>{$row['waktu']}</td>
                                </tr>";
                                $no++;
                            }
                            
                            if (mysqli_num_rows($data) == 0) {
                                echo "<tr>
                                    <td colspan='6' class='empty-state'>
                                        <i class='fas fa-inbox'></i>
                                        <p>Tidak ada data tamu</p>
                                    </td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_halaman > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                            <li class="page-item <?= ($i == $halaman) ? 'active' : '' ?>">
                                <a class="page-link" href="?halaman=<?= $i ?>&tanggal_awal=<?= urlencode($tanggal_awal) ?>&tanggal_akhir=<?= urlencode($tanggal_akhir) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
