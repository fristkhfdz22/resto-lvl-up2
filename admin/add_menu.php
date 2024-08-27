<?php
session_start();
// Memeriksa apakah pengguna sudah login; jika tidak, arahkan ke halaman login.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../db_connect.php'; // Menghubungkan ke database.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form yang dikirim melalui metode POST.
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    // Mengambil nama file gambar yang diunggah.
    $image = $_FILES['image']['name'];
    
    // Menetapkan direktori target untuk menyimpan gambar yang diunggah.
    $target_dir = "../images/";
    $target_file = $target_dir . basename($image); // Menyusun jalur lengkap file.
    $uploadOk = 1; // Flag untuk menentukan apakah file bisa diunggah.
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // Mengambil ekstensi file.

    // Memeriksa apakah file yang diunggah benar-benar gambar.
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        echo "File bukan gambar.";
        $uploadOk = 0; // Jika bukan gambar, set flag menjadi 0.
    }

    // Memeriksa ukuran file (maksimal 5MB).
    if ($_FILES['image']['size'] > 5000000) {
        echo "Maaf, file Anda terlalu besar.";
        $uploadOk = 0; // Jika ukuran file lebih besar dari 5MB, set flag menjadi 0.
    }

    // Memeriksa format file yang diizinkan (hanya JPG, JPEG, PNG, & GIF).
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0; // Jika format file tidak sesuai, set flag menjadi 0.
    }

    // Memeriksa apakah $uploadOk diset menjadi 0 oleh kesalahan sebelumnya.
    if ($uploadOk == 0) {
        echo "Maaf, file Anda tidak dapat diunggah.";
    } else {
        // Jika semuanya baik, mencoba mengunggah file.
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Jika file berhasil diunggah, menyisipkan data item menu ke dalam basis data.
            $sql = "INSERT INTO menu_items (name, description, price, image) VALUES ('$name', '$description', '$price', '$image')";
            if (mysqli_query($conn, $sql)) {
                // Jika berhasil, mengarahkan kembali ke halaman manajemen menu.
                header("Location: admin_menu.php");
                exit();
            } else {
                // Jika terjadi kesalahan saat menyisipkan data, menampilkan pesan error.
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            // Jika terjadi kesalahan saat mengunggah file, menampilkan pesan error.
            echo "Maaf, terjadi kesalahan saat mengunggah file Anda.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Item Menu</title>
    <!-- Menyertakan CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link" href="admin_menu.php">Kelola Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_orders.php">Kelola Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_panel.php">Kontak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Kontainer untuk menambahkan item menu baru -->
    <div class="container mt-4">
        <h1 class="mb-4">Tambah Item Menu Baru</h1>
        <!-- Form untuk menambahkan item menu baru -->
        <form action="add_menu.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Nama:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi:</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Harga:</label>
                <input type="number" class="form-control" step="0.01" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Gambar:</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Tambahkan Item</button>
        </form>
        <a href="admin_menu.php" class="btn btn-secondary mt-3">Kembali ke Manajemen Menu</a>
    </div>

    <!-- Menyertakan JS Bootstrap dan dependensinya -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
