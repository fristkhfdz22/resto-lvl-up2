<?php
session_start(); // Memulai sesi.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Memeriksa apakah pengguna sudah login; jika tidak, arahkan ke halaman login.
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Menyertakan Bootstrap CSS untuk styling dan layout responsif -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Menyertakan file CSS kustom untuk styling tambahan -->
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <!-- Navbar untuk navigasi dalam panel admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin_menu.php">Manage Menu</a> <!-- Link ke halaman manajemen menu -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_orders.php">Manage Orders</a> <!-- Link ke halaman manajemen pesanan -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_panel.php">Contact</a> <!-- Link ke halaman kontak -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a> <!-- Link untuk logout -->
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Kontainer utama untuk konten halaman dashboard -->
    <div class="container mt-4">
        <h1>Admin Dashboard</h1> <!-- Judul halaman -->
    </div>

    <!-- Menyertakan JavaScript Bootstrap dan dependensinya -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
