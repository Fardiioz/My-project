<?php
session_start();
include 'koneksi.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM adm WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['status'] == 1) { // assuming 1 is active
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_email'] = $row['email'];
                header('Location: Dashboard_admin.php');
                exit();
            } else {
                $message = 'Akun tidak aktif.';
            }
        } else {
            $message = 'Email atau password salah.';
        }
    } elseif (isset($_POST['forgot'])) {
        $email = $_POST['forgot_email'];

        $query = "SELECT password FROM adm WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $message = 'Password Anda: ' . $row['password'];
        } else {
            $message = 'Email tidak ditemukan.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form id="login-form" method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <p><a href="#" id="forgot-link">Lupa Password?</a></p>

        <form id="forgot-form" method="POST" action="" style="display: none;">
            <input type="email" name="forgot_email" placeholder="Masukkan Email" required>
            <button type="submit" name="forgot">Tampilkan Password</button>
            <p><a href="#" id="back-link">Kembali ke Login</a></p>
        </form>
    </div>

    <script src="script.js"></script>
    <script>
        document.getElementById('forgot-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('forgot-form').style.display = 'block';
        });

        document.getElementById('back-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('forgot-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        });
    </script>
</body>
</html>
