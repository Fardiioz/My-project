<?php
session_start();
include "../koneksi.php";

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $level = $_POST['level'] ?? 'off';

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($level)) {
        $error = "Semua field harus diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sama.";
    } elseif (!in_array($level, ['off', 'on'])) {
        $error = "Level tidak valid.";
    } else {
        // Check if email already exists
        $query = "SELECT * FROM admin WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            // Insert new admin user
            $insert_query = "INSERT INTO admin (username, email, password, level) VALUES ('$username', '$email', '$password', '$level')";
            if (mysqli_query($conn, $insert_query)) {
                $success = "Registrasi berhasil. Silakan <a href='index.php'>login</a>.";
            } else {
                $error = "Terjadi kesalahan saat registrasi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrasi Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #ffffffff, #3498db);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .register-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .register-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 6px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .error {
            background: #ffe6e6;
            color: #c0392b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }
        .success {
            background: #e6ffe6;
            color: #27ae60;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }
        .link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .link a {
            color: #3498db;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Registrasi Admin</h2>

    <?php if (!empty($error)) { ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php } ?>

    <?php if (!empty($success)) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required placeholder="Masukkan username" />
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Masukkan email" />
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Masukkan password" />
        </div>
        <div class="form-group">
            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required placeholder="Konfirmasi password" />
        </div>
        <div class="form-group">
            <label for="level">Level:</label>
            <select name="level" id="level" required>
                <option value="off">off</option>
                <option value="on">on</option>
            </select>
        </div>
        <button type="submit" class="btn">Daftar</button>
    </form>

    <div class="link">
        Sudah punya akun? <a href="index.php">Login di sini</a>
    </div>
</div>

</body>
</html>
