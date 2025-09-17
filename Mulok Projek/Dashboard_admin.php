<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel Meaning Betawi- Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <h2>Artikel</h2>
            </div>
            <ul class="nav-menu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#artikel">Artikel</a></li>
                <li><a href="#timeline">Timeline</a></li>
                <li><a href="#tentang">Tentang</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Artikel Meaning Betawi</h1>
            <p>Betawi adalah sebutan bagi penduduk asli Jakarta. Kata Betawi sendiri berasal dari kata "Batavia", yaitu nama lama Jakarta pada masa penjajahan Belanda. Masyarakat Betawi lahir dari hasil percampuran berbagai etnis yang datang ke Jakarta sejak abad ke-17, seperti Sunda, Jawa, Arab, Tionghoa, Portugis, hingga Belanda. Karena itu, budaya Betawi punya ciri khas yang unik—campuran beragam budaya yang kemudian membentuk identitas baru.</p>
            <button class="cta-button">Jelajahi Artikel</button>
        </div>
    </section>

    <!-- Makna Betawi Section -->
    <section id="makna-betawi" class="makna-betawi-section">
        <div class="container">
            <h2 class="section-title">Makna & Budaya Betawi</h2>
            <div class="makna-betawi-content">
                <div class="makna-betawi-text">
                    <div class="makna-item">
                        <h3>1. Identitas Lokal</h3>
                        <p>Betawi menandakan jati diri penduduk asli Jakarta di tengah arus modernisasi.</p>
                    </div>
                    <div class="makna-item">
                        <h3>2. Perpaduan Budaya</h3>
                        <p>Budaya Betawi adalah bukti nyata adanya akulturasi (percampuran budaya) yang harmonis.</p>
                    </div>
                    <div class="makna-item">
                        <h3>3. Warisan Leluhur</h3>
                        <p>Seni, bahasa, pakaian, dan tradisi Betawi adalah peninggalan berharga yang harus dilestarikan.</p>
                    </div>
                    <div class="makna-item">
                        <h3>4. Cermin Keberagaman Indonesia</h3>
                        <p>Betawi menunjukkan bahwa keberagaman bisa melahirkan satu identitas yang kuat.</p>
                    </div>
                </div>
                
                <!-- Contoh Budaya Betawi -->
                <div class="budaya-betawi-grid">
                    <h3 style="text-align: center; margin: 3rem 0 2rem; color: #2c3e50;">Contoh Budaya Betawi</h3>
                    <div class="artikel-grid">
