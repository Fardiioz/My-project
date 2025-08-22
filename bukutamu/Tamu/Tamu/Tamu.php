<?php
// Memulai sesi untuk menyimpan data pengguna
session_start();

// Memeriksa apakah username ada dalam session, jika tidak, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: ../admin/index.php");
    exit(); // Menghentikan eksekusi script setelah redirect
}

// Menginclude file koneksi ke database
include '../koneksi.php';
$username = $_SESSION['username']; // Mengambil username dari session

// Ambil input filter tanggal dari query string
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

// Query untuk mengambil data tamu dengan limit dan offset, diurutkan dari yang terbaru
$sql = "SELECT * FROM tamu $filter_sql ORDER BY tanggal DESC, waktu DESC LIMIT $mulai, $batas";
$data = mysqli_query($conn, $sql);

// Hitung total data tamu yang sesuai dengan filter
$total_data = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tamu $filter_sql"));
$total_halaman = ceil($total_data / $batas);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tamu - Buku Tamu Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 20px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header h2 {
            color: white;
            font-weight: 300;
            font-size: 1.8rem;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-section label {
            font-weight: 500;
            color: #555;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .filter-section input[type="date"] {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .filter-section input[type="date"]:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a6fd8;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e9ecef;
        }

        td {
            padding: 12px 15px;
            border-bottom: 1px solid #f1f3f4;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .delete-btn {
            color: #dc3545;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .delete-btn:hover {
            background-color: #f8d7da;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 30px;
        }

        .pagination a {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            text-decoration: none;
            color: #667eea;
            transition: all 0.3s;
        }

        .pagination a.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination a:hover:not(.active) {
            background: #f8f9fa;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            margin-top: 20px;
            font-weight: 500;
        }

        .back-link:hover {
            color: #5a6fd8;
        }

        @media (max-width: 768px) {
            .filter-section {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-section input[type="date"] {
                width: 100%;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2><i class="fas fa-users"></i> Daftar Tamu</h2>
    </div>

    <div class="container">
        <form method="GET" action="" class="filter-section">
            <label>
                <i class="fas fa-calendar-alt"></i> Dari:
                <input type="date" name="tanggal_awal" value="<?= htmlspecialchars($tanggal_awal) ?>">
            </label>
            
            <label>
                <i class="fas fa-calendar-alt"></i> Sampai:
                <input type="date" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir) ?>">
            </label>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tampilkan
            </button>
            
            <a href="tamu.php" class="btn btn-secondary">
                <i class="fas fa-refresh"></i> Reset
            </a>
        </form>

        <?php if (isset($_GET['message'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> No</th>
                        <th><i class="fas fa-user"></i> Nama</th>
                        <th><i class="fas fa-building"></i> Instansi</th>
                        <th><i class="fas fa-tasks"></i> Keperluan</th>
                        <th><i class="fas fa-calendar"></i> Tanggal</th>
                        <th><i class="fas fa-clock"></i> Waktu</th>
                        <th><i class="fas fa-cog"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = $mulai + 1;
                    if (mysqli_num_rows($data) > 0):
                        while ($row = mysqli_fetch_assoc($data)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['instansi']) ?></td>
                                <td><?= htmlspecialchars($row['keperluan']) ?></td>
                                <td><?= htmlspecialchars($row['tanggal']) ?></td>
                                <td><?= htmlspecialchars($row['waktu']) ?></td>
                                <td>
                                    <a href="delete.php?id=<?= $row['id'] ?>" 
                                       class="delete-btn"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus tamu ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 10px;"></i>
                                <p>Tidak ada data tamu</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_halaman > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                    <a href="?halaman=<?= $i ?>&tanggal_awal=<?= urlencode($tanggal_awal) ?>&tanggal_akhir=<?= urlencode($tanggal_akhir) ?>"
                       class="<?= ($i == $halaman) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <a href="../admin/dashboard.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
