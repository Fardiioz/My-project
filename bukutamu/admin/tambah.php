<?php
include '../koneksi.php'; // Include database connection

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if email already exists
    $cek = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $message = "<div class='alert alert-danger'>Email sudah terdaftar!</div>";
    } else {
        // Insert new admin
        $query = "INSERT INTO admin (email, username, password) VALUES ('$email', '$username', '$password')"; // IMPORTANT: Hash passwords in real applications!
        if (mysqli_query($conn, $query)) {
            // If successful, redirect to t_admin.php
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Tambah Admin</title>
    <style>
        body {
            background: linear-gradient(to right, #aa3448ff, #dd3442ff); /* Example gradient, adjust as needed */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            padding: 3rem;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(99, 17, 17, 1);
        }
        h3 {
            color: #444; /* Darker color for heading */
        }
    </style>
</head>
<body>
    <div class="card col-md-6 bg-light">
        <h3 class="card-title text-center mb-4">Tambah Admin Baru</h3>
        <?php echo $message; // Display message ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" maxlength="8" class="form-control" required>
                <div class="form-text">Maksimal 8 karakter sesuai struktur tabel.</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Tambah Admin</button>
            <a href="t_admin.php" class="btn btn-secondary w-100 mt-2">Batal</a>
        </form>
    </div>
</body>
</html>