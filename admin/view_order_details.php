<?php
// Memulai sesi untuk melacak status login pengguna
session_start();

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Mengarahkan ke halaman login jika pengguna belum login
    header("Location: login.php");
    exit();
}

// Menyertakan file koneksi database (sesuaikan jalur jika diperlukan)
include __DIR__ . '/../db_connect.php';

// Mendapatkan ID pesanan dari parameter GET
$id = $_GET['id'];

// Mengambil detail pesanan dari database berdasarkan ID pesanan
$sql = "SELECT * FROM order_details WHERE order_id='$id'";
$result = mysqli_query($conn, $sql);

// Mengambil informasi pesanan dari database berdasarkan ID pesanan
$order_sql = "SELECT * FROM orders WHERE id='$id'";
$order_result = mysqli_query($conn, $order_sql);
$order = mysqli_fetch_assoc($order_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <!-- Memuat Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            /* Menyembunyikan elemen dengan kelas no-print saat pencetakan */
            .no-print {
                display: none;
            }
            /* Styling tabel saat dicetak */
            .printable {
                width: 100%;
                border: none;
                font-size: 12pt;
            }
            .printable th, .printable td {
                border: 1px solid #000;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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
        <h1 class="mb-4">Order Details</h1>
        <!-- Menampilkan ID pesanan -->
        <h2 class="mb-3">Order ID: <?php echo htmlspecialchars($id); ?></h2>
        <!-- Menampilkan informasi pelanggan dan total harga -->
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Total Price:</strong> Rp<?php echo number_format($order['total_price'], 2); ?></p>

        <!-- Tabel detail pesanan -->
        <table class="table table-striped printable">
            <thead>
                <tr>
                    <th>Dish ID</th>
                    <th>Quantity</th>
                    <th>Price per Item</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($detail = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detail['dish_id']); ?></td>
                    <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                    <td>Rp<?php echo number_format($detail['price_per_item'], 2); ?></td>
                    <td>Rp<?php echo number_format($detail['total'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Tombol untuk kembali ke halaman pesanan dan untuk mencetak -->
        <a href="admin_orders.php" class="btn btn-secondary no-print">Back to Orders</a>
        <button onclick="window.print()" class="btn btn-primary no-print">Print</button>
    </div>

    <!-- Memuat Bootstrap JS dan dependensinya -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi database
mysqli_close($conn);
?>
