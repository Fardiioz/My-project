<?php
session_start();
include '../koneksi.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../admin_login.php');
    exit();
}

// Get category name from filename
$category_name = 'Kuliner';
$table_name = 'kategori_kuliner';

// Create table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS $table_name (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $create_table)) {
    die("Error creating table: " . mysqli_error($conn));
}

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action == 'create') {
            $nama = mysqli_real_escape_string($conn, $_POST['nama']);
            $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

            // Handle file upload
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $upload_dir = '../uploads/kuliner/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = time() . '_' . basename($_FILES['image']['name']);
                $image_path = $upload_dir . $image_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $image = 'uploads/kuliner/' . $image_name;
                }
            }

            $query = "INSERT INTO $table_name (nama, deskripsi, image) VALUES ('$nama', '$deskripsi', '$image')";
            if (mysqli_query($conn, $query)) {
                $message = "Data kuliner berhasil ditambahkan!";
                $message_type = 'success';
            } else {
                $message = "Error: " . mysqli_error($conn);
                $message_type = 'error';
            }
        } elseif ($action == 'update') {
            $id = (int)$_POST['id'];
            $nama = mysqli_real_escape_string($conn, $_POST['nama']);
            $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

            $query = "UPDATE $table_name SET nama='$nama', deskripsi='$deskripsi'";

            // Handle file upload for update
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $upload_dir = '../uploads/kuliner/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $image_name = time() . '_' . basename($_FILES['image']['name']);
                $image_path = $upload_dir . $image_name;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                    $image = 'uploads/kuliner/' . $image_name;
                    $query .= ", image='$image'";
                }
            }

            $query .= " WHERE id=$id";

            if (mysqli_query($conn, $query)) {
                $message = "Data kuliner berhasil diupdate!";
                $message_type = 'success';
            } else {
                $message = "Error: " . mysqli_error($conn);
                $message_type = 'error';
            }
        } elseif ($action == 'delete') {
            $id = (int)$_POST['id'];

            // Get image path before deleting
            $get_image_query = "SELECT image FROM $table_name WHERE id=$id";
            $result = mysqli_query($conn, $get_image_query);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if ($row['image'] && file_exists('../' . $row['image'])) {
                    unlink('../' . $row['image']);
                }
            }

            $query = "DELETE FROM $table_name WHERE id=$id";
            if (mysqli_query($conn, $query)) {
                $message = "Data kuliner berhasil dihapus!";
                $message_type = 'success';
            } else {
                $message = "Error: " . mysqli_error($conn);
                $message_type = 'error';
            }
        }
    }
}

// Get data for display
$query = "SELECT * FROM $table_name ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola <?php echo $category_name; ?></title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .crud-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .image-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .action-buttons button {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <h1>Dashboard Admin</h1>
            <ul class="navbar-menu">
                <li><a href="../Dashboard_admin.php">Beranda</a></li>
                <li><a href="../kelola_admin.php">Kelola Admin</a></li>
                <li><a href="../kelola_kategori.php">Kelola Kategori</a></li>
                <li><a href="../Dashboard_admin.php?logout=true">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="crud-container">
        <h2>Kelola <?php echo $category_name; ?></h2>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Create/Edit Form -->
        <div class="form-section">
            <h3 id="form-title">Tambah <?php echo $category_name; ?> Baru</h3>
            <form id="crud-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="form-action" value="create">
                <input type="hidden" name="id" id="form-id" value="">

                <div class="form-group">
                    <label for="nama">Nama:</label>
                    <input type="text" id="nama" name="nama" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi:</label>
                    <textarea id="deskripsi" name="deskripsi" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Gambar:</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    <div id="image-preview" style="margin-top: 10px;"></div>
                </div>

                <button type="submit" class="btn btn-primary" id="submit-btn">Simpan</button>
                <button type="button" class="btn btn-secondary" id="cancel-btn" style="display: none;">Batal</button>
            </form>
        </div>

        <!-- Data Table -->
        <div class="table-container">
            <h3>Daftar <?php echo $category_name; ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 100)) . (strlen($row['deskripsi']) > 100 ? '...' : ''); ?></td>
                                <td>
                                    <?php if ($row['image']): ?>
                                        <img src="../<?php echo htmlspecialchars($row['image']); ?>" alt="Preview" class="image-preview">
                                    <?php else: ?>
                                        <span>Tidak ada gambar</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                <td class="action-buttons">
                                    <button class="btn btn-success" onclick="editItem(<?php echo $row['id']; ?>, '<?php echo addslashes($row['nama']); ?>', '<?php echo addslashes($row['deskripsi']); ?>')">Edit</button>
                                    <button class="btn btn-danger" onclick="deleteItem(<?php echo $row['id']; ?>)">Hapus</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Belum ada data <?php echo strtolower($category_name); ?>.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 1px solid #ddd; padding: 5px;">';
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });

        // Edit function
        function editItem(id, nama, deskripsi) {
            document.getElementById('form-action').value = 'update';
            document.getElementById('form-id').value = id;
            document.getElementById('nama').value = nama;
            document.getElementById('deskripsi').value = deskripsi;
            document.getElementById('form-title').textContent = 'Edit <?php echo $category_name; ?>';
            document.getElementById('submit-btn').textContent = 'Update';
            document.getElementById('cancel-btn').style.display = 'inline-block';

            // Scroll to form
            document.querySelector('.form-section').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Cancel edit function
        document.getElementById('cancel-btn').addEventListener('click', function() {
            document.getElementById('crud-form').reset();
            document.getElementById('form-action').value = 'create';
            document.getElementById('form-id').value = '';
            document.getElementById('form-title').textContent = 'Tambah <?php echo $category_name; ?> Baru';
            document.getElementById('submit-btn').textContent = 'Simpan';
            document.getElementById('cancel-btn').style.display = 'none';
            document.getElementById('image-preview').innerHTML = '';
        });

        // Delete function
        function deleteItem(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <script src="../script.js"></script>
</body>
</html>
