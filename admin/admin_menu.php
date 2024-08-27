<?php
session_start(); // Memulai sesi PHP

// Memeriksa apakah pengguna sudah login; jika tidak, arahkan ke halaman login.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../db_connect.php'; // Menyertakan file koneksi basis data

// Mengambil semua item menu dari tabel `menu_items` di basis data
$sql = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu Items</title>
    <!-- Menyertakan CSS Bootstrap untuk styling dan layout responsif -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Menyertakan file CSS kustom untuk styling tambahan -->
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <!-- Navbar untuk navigasi dalam panel admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <!-- Tombol toggler untuk tampilan mobile -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Menu navigasi -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Link ke halaman Manage Menu -->
                <li class="nav-item">
                    <a class="nav-link" href="admin_menu.php">Manage Menu</a>
                </li>
                <!-- Link ke halaman Manage Orders -->
                <li class="nav-item">
                    <a class="nav-link" href="admin_orders.php">Manage Orders</a>
                </li>
                <!-- Link ke halaman Contact -->
                <li class="nav-item">
                    <a class="nav-link" href="admin_panel.php">Contact</a>
                </li>
                <!-- Link untuk logout -->
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Kontainer utama untuk konten halaman -->
    <div class="container mt-4">
        <h1 class="mb-4">Manage Menu Items</h1>
        <!-- Tombol untuk menambahkan item menu baru -->
        <a href="add_menu.php" class="btn btn-primary mb-3">Add New Item</a>
        <!-- Tabel untuk menampilkan daftar item menu -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th> <!-- Kolom ID -->
                    <th>Name</th> <!-- Kolom Nama -->
                    <th>Description</th> <!-- Kolom Deskripsi -->
                    <th>Price</th> <!-- Kolom Harga -->
                    <th>Image</th> <!-- Kolom Gambar -->
                    <th>Actions</th> <!-- Kolom Aksi (Edit/Delete) -->
                </tr>
            </thead>
            <tbody>
                <!-- Looping untuk menampilkan setiap item menu dari hasil query -->
                <?php while ($item = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <!-- Menampilkan ID item menu dengan htmlspecialchars untuk mencegah XSS -->
                    <td><?php echo htmlspecialchars($item['id']); ?></td>
                    <!-- Menampilkan Nama item menu -->
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <!-- Menampilkan Deskripsi item menu -->
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <!-- Menampilkan Harga item menu dengan format Rupiah -->
                    <td>Rp<?php echo number_format($item['price'], 2); ?></td>
                    <!-- Menampilkan Gambar item menu sebagai thumbnail -->
                    <td>
                        <img src="../images/<?php echo htmlspecialchars($item['image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             class="img-thumbnail" width="100">
                    </td>
                    <!-- Menampilkan tombol Edit dan Delete untuk setiap item -->
                    <td>
                        <!-- Tombol Edit yang mengarahkan ke halaman edit_menu.php dengan parameter ID -->
                        <a href="edit_menu.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <!-- Tombol Delete yang mengarahkan ke halaman delete_menu.php dengan parameter ID dan konfirmasi sebelum menghapus -->
                        <a href="delete_menu.php?id=<?php echo $item['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Tombol untuk kembali ke halaman Dashboard Admin -->
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <!-- Menyertakan JavaScript Bootstrap dan dependensinya -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn); // Menutup koneksi basis data
?>
