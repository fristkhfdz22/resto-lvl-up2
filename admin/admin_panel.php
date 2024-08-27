<?php
session_start(); // Memulai sesi untuk pengguna yang sedang login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Jika pengguna belum login, arahkan ke halaman login
    exit(); // Hentikan eksekusi kode selanjutnya
}

include __DIR__ . '/../db_connect.php'; // Menyertakan file untuk koneksi database

// Menjalankan query SQL untuk mengambil semua data dari tabel kontak, diurutkan berdasarkan tanggal pengiriman terbaru
$sql = "SELECT * FROM contacts ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $sql); // Menjalankan query dan menyimpan hasilnya

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Menyertakan Bootstrap CSS untuk styling -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Menyertakan file CSS khusus -->
</head>
<body>
    <!-- Navbar untuk navigasi di admin panel -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin_menu.php">Manage Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_orders.php">Manage Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_panel.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Tabel untuk menampilkan pesan dan reservasi yang sudah dikirimkan -->
        <h2 class="text-center">Submitted Messages & Reservations</h2>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Reservation Date</th>
                    <th>Reservation Time</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <!-- Menampilkan data yang diambil dari database ke dalam tabel -->
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?php echo htmlspecialchars($row['reservation_date'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['reservation_time'] ?? ''); ?></td>
                        <td><?php echo $row['submitted_at']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Menyertakan Bootstrap JS dan dependensinya -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn); // Menutup koneksi database
?>
