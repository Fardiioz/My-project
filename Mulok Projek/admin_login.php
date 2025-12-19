<?php
session_start();
include 'koneksi.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query cek user
    $query = "SELECT * FROM admin WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // ⚠️ karena password di DB masih plaintext, cukup bandingkan langsung
        if ($password === $row['password']) {
            if ($row['status'] === 'on') { // cek status aktif
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['status'] = $row['status'];

                // (opsional) log login
                // $log_query = "INSERT INTO login_log (admin_id) VALUES ('{$row['id']}')";
                // mysqli_query($conn, $log_query);

                header("Location: dashboard_admin.php");
                exit();
            } else {
                $message = "❌ Akun Anda belum aktif (status OFF).";
            }
        } else {
            $message = "⚠️ Email atau password salah.";
        }
    } else {
        $message = "⚠️ Email atau password salah.";
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <style>
    /* Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #4f46e5, #9333ea);
    }

    .login-box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      width: 360px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
      animation: fadeIn 0.6s ease-in-out;
      text-align: center;
    }

    .login-box h2 {
      margin-bottom: 25px;
      color: #4f46e5;
    }

    .login-box input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 12px;
      outline: none;
      transition: 0.3s;
    }

    .login-box input:focus {
      border-color: #9333ea;
      box-shadow: 0 0 8px rgba(147, 51, 234, 0.4);
    }

    .login-box button {
      width: 100%;
      padding: 12px;
      margin-top: 15px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #4f46e5, #9333ea);
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }

    .login-box button:hover {
      background: linear-gradient(135deg, #4338ca, #7e22ce);
      transform: translateY(-2px);
    }

    .login-box p {
      margin-top: 15px;
      font-size: 14px;
    }

    .login-box a {
      color: #4f46e5;
      text-decoration: none;
      font-weight: 500;
    }

    .login-box a:hover {
      text-decoration: underline;
    }

    /* Popup message */
    .message {
      display: none;
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 20px;
      border-radius: 12px;
      color: #fff;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
      animation: slideIn 0.5s ease, fadeOut 0.5s ease 4s forwards;
      z-index: 9999;
    }

    .message.error { background: #f44336; }
    .message.success { background: #4caf50; }

    /* Animasi */
    @keyframes slideIn {
      from {opacity: 0; transform: translateX(100%);}
      to {opacity: 1; transform: translateX(0);}
    }
    @keyframes fadeOut {
      to {opacity: 0; transform: translateX(100%);}
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Login Admin</h2>
    <?php if ($message): ?>
      <?php 
        $class = (strpos($message, 'salah') !== false || strpos($message, 'belum') !== false) 
                  ? 'message error' : 'message success';
        echo "<div class='$class' id='popupMessage'>$message</div>";
      ?>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p>Lupa password? <a href="#">Hubungi Admin</a></p>
  </div>

  <script>
    // Popup auto show & hide
    const popup = document.getElementById("popupMessage");
    if (popup) {
      popup.style.display = "block";
      setTimeout(() => {
        popup.style.display = "none";
      }, 4000);
    }
  </script>
</body>
</html>
</html>
