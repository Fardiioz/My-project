<?php
include '../koneksi.php';

// Ambil tahun filter
$tahun_filter = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date("Y");

// Pencarian nama
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$sql_count = "SELECT COUNT(*) as total FROM tamu WHERE YEAR(tanggal) = $tahun_filter";
if (!empty($search)) {
    $sql_count .= " AND nama LIKE '%$search%'";
}
$result_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_data = $row_count['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data
$sql_data = "SELECT * FROM tamu WHERE YEAR(tanggal) = $tahun_filter";
if (!empty($search)) {
    $sql_data .= " AND nama LIKE '%$search%'";
}
$sql_data .= " ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";
$result_data = mysqli_query($conn, $sql_data);

// Download CSV
if (isset($_GET['download']) && $_GET['download'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="kehadiran_'.$tahun_filter.'.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['No', 'Nama', 'Tanggal', 'Waktu']);

    $no = 1;
    $sql_all = "SELECT * FROM tamu WHERE YEAR(tanggal) = $tahun_filter";
    if (!empty($search)) {
        $sql_all .= " AND nama LIKE '%$search%'";
    }
    $sql_all .= " ORDER BY tanggal DESC";
    $result_all = mysqli_query($conn, $sql_all);

    while ($row = mysqli_fetch_assoc($result_all)) {
        fputcsv($output, [$no++, $row['nama'], $row['tanggal'], $row['waktu']]);
    }
    fclose($output);
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kehadiran - Clean Design</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --background-color: #f8fafc;
            --card-background: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --hover-color: #f1f5f9;
            --success-color: #10b981;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--background-color);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            color: white;
            padding: 2.5rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .filter-card {
            background: var(--card-background);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background-color: #475569;
            transform: translateY(-1px);
        }

        .table-container {
            background: var(--card-background);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: var(--hover-color);
        }

        .table tbody td {
            padding: 1rem;
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .pagination {
            margin-top: 2rem;
        }

        .page-link {
            color: var(--primary-color);
            border: 1px solid var(--border-color);
            margin: 0 0.125rem;
            border-radius: 0.375rem;
        }

        .page-link:hover {
            background-color: var(--hover-color);
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            
            .header-section {
                padding: 1.5rem;
            }
            
            .header-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Rekap Kehadiran Tamu <?= $tahun_filter ?></h2>

    <!-- Filter tahun & pencarian -->
    <form method="get" class="row mb-3">
        <div class="col-md-3">
            <label for="tahun" class="form-label">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="form-select" onchange="this.form.submit()">
                <?php for ($tahun = 2019; $tahun <= date("Y"); $tahun++): ?>
                <option value="<?= $tahun ?>" <?= $tahun == $tahun_filter ? 'selected' : '' ?>><?= $tahun ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-5">
            <label for="search" class="form-label">Cari Nama:</label>
            <input type="text" name="search" id="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Cari nama...">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="?tahun=<?= $tahun_filter ?>&search=<?= urlencode($search) ?>&download=csv" class="btn btn-success w-100">
                &#128190; Download CSV
            </a>
        </div>
    </form>

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-bordered bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result_data) > 0): ?>
                <?php $no = $offset + 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result_data)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['waktu'] ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                <a class="page-link" href="?tahun=<?= $tahun_filter ?>&search=<?= urlencode($search) ?>&page=<?= $p ?>">
                    <?= $p ?>
                </a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <div class="mt-4">
        <a href="../admin/dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>