<?php
include '../koneksi.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah email sudah terdaftar
    $cek = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $message = "<div class='alert alert-danger'>Email sudah terdaftar!</div>";
    } else {
        $query = "INSERT INTO admin (email, username, password, level) VALUES ('$email', '$username', '$password', 'off')";
        if (mysqli_query($conn, $query)) {
            // Jika berhasil, redirect ke halaman t_admin.php
            header("location: t_admin.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Gagal menambahkan admin!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tambah Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <style>
        body {
            background: linear-gradient(to right, #3a7edd, #7bc0f9);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .card {
            padding: 2.5rem 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            max-width: 480px;
            width: 100%;
            background: #ffffffcc;
        }
        h3 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        label {
            font-weight: 600;
        }
        .form-text {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .btn-primary {
            font-weight: 600;
        }
        .btn-secondary {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card shadow-sm">
        <h3>Tambah Admin Baru</h3>
        <?= $message ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" maxlength="8" required />
                <div class="form-text">Maksimal 8 karakter sesuai struktur tabel.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Tambah Admin</button>
            <a href="t_admin.php" class="btn btn-secondary w-100 mt-3">Batal</a>
        </form>
    </div>
</body>
</html>
