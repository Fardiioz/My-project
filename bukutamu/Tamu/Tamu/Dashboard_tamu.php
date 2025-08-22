<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../admin/index.php');
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tamu - SMKN 71 Jakarta</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 2rem 0;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .header i {
            margin-right: 0.5rem;
            color: #ffd700;
        }

        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-text {
            margin-bottom: 2rem;
        }

        .welcome-text h2 {
            color: #2c3e50;
            font-size: 2rem;
            font-weight: 400;
            margin-bottom: 0.5rem;
        }

        .welcome-text p {
            color: #7f8c8d;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .btn-isi {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-isi:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .btn-isi i {
            font-size: 1.2rem;
        }

        .footer {
            background: rgba(0, 0, 0, 0.2);
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
            padding: 1.5rem;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .content-card {
                margin: 1rem;
                padding: 2rem;
            }
            
            .welcome-text h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1><i class="fas fa-building"></i> Buku Tamu Digital</h1>
    </header>

    <main class="main-container">
        <div class="content-card">
            <div class="welcome-text">
                <h2>Selamat Datang di SMKN 71 Jakarta</h2>
                <p>Silakan isi form buku tamu untuk melakukan kunjungan ke sekolah kami.</p>
            </div>
            <a href="form_tamu.php" class="btn-isi">
                <i class="fas fa-pen"></i>
                Isi Buku Tamu
            </a>
        </div>
    </main>

    <footer class="footer">
        &copy; <?= date('Y') ?> SMKN 71 Jakarta – Sistem Buku Tamu Digital
    </footer>
</body>
</html>
