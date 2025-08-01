<?php
session_start();
include "../koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    // Validate input (you can add more validation as needed)
    if (!empty($email) && !empty($username) && !empty($password) && !empty($level)) {
        // Check if email already exists
        $cek = mysqli_query($conn, "SELECT * FROM admin WHERE email = '$email'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Email sudah terdaftar!');</script>";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin into the database with hashed password and level
            $query = "INSERT INTO admin (email, username, password, level) VALUES ('$email', '$username', '$hashed_password', '$level')";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Admin berhasil ditambahkan!'); window.location.href='t_admin.php';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Semua field harus diisi!');</script>";
    }
}
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
     $level = $_POST['level'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
           background: linear-gradient(to right, #ffffff, #6dd5fa);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .form-container {
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
        .form-container h4 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="container form-container">
    <h4 class="mb-4">Tambah Admin</h4>
    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
       <div class="mb-3">
    <label class="form-label">Level</label>
    <select class="form-select" name="level" required>
        <option value="">-- Pilih Level --</option>
        <option value="on">On</option>
        <option value="off">Off</option>
    </select>
</div>

    <button type="submit" class="btn btn-primary" name="submit">Tambah</button>

        <a href="t_admin.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

</body>
</html>