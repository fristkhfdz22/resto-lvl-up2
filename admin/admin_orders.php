<?php
// Memulai sesi
session_start();

// Memeriksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.php");
    exit();
}

// Menghubungkan ke database
include __DIR__ . '/../db_connect.php';

// Menangani penghapusan jika parameter 'delete_id' ada dan valid
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Pertama, hapus detail pesanan terkait dari tabel 'order_details'
    $delete_details_sql = "DELETE FROM order_details WHERE order_id = ?";
    $stmt = mysqli_prepare($conn, $delete_details_sql);
    mysqli_stmt_bind_param($stmt, 'i', $delete_id);
    mysqli_stmt_execute($stmt);

    // Kemudian, hapus pesanan dari tabel 'orders'
    $delete_sql = "DELETE FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt, 'i', $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, tampilkan pesan sukses
        $message = "<div class='alert alert-success' role='alert'>Order deleted successfully.</div>";
    } else {
        // Jika gagal, tampilkan pesan kesalahan
        $message = "<div class='alert alert-danger' role='alert'>Error deleting order: " . mysqli_error($conn) . "</div>";
    }
}

// Menangani pembaruan status pesanan
if (isset($_POST['update_status']) && isset($_POST['status']) && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Memperbarui status pesanan di database
    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, 'si', $status, $order_id);
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, tampilkan pesan sukses
        $message = "<div class='alert alert-success' role='alert'>Order status updated successfully.</div>";
    } else {
        // Jika gagal, tampilkan pesan kesalahan
        $message = "<div class='alert alert-danger' role='alert'>Error updating status: " . mysqli_error($conn) . "</div>";
    }
}

// Mengambil data pesanan dari database
$sql = "SELECT * FROM orders";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <!-- Menghubungkan file CSS Bootstrap untuk styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .alert {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
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
    <h1 class="mb-4">Manage Orders</h1>

    <!-- Tampilkan notifikasi jika ada -->
    <?php if (isset($message)) echo "<div id='notification' class='alert alert-info alert-dismissible fade show' role='alert'>
        $message
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>
    </div>"; ?>

    <form action="admin_orders.php" method="post">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop melalui setiap pesanan dan tampilkan dalam tabel -->
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td>Rp<?php echo number_format($order['total_price'], 2); ?></td>
                    <td>
                        <!-- Form untuk memperbarui status pesanan -->
                        <form action="admin_orders.php" method="post">
                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="Pending" <?php echo ($order['status'] === 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Processing" <?php echo ($order['status'] === 'Processing') ? 'selected' : ''; ?>>Processing</option>
                                <option value="Completed" <?php echo ($order['status'] === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="Cancelled" <?php echo ($order['status'] === 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td>
                        <!-- Tombol untuk melihat detail pesanan -->
                        <a href="view_order_details.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-info btn-sm">View Details</a>
                    </td>
                    <td>
                        <!-- Tombol untuk menghapus pesanan dengan konfirmasi -->
                        <a href="?delete_id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Tombol untuk kembali ke Dashboard -->
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>

<!-- Menghubungkan file JavaScript Bootstrap dan jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Menutup koneksi database
mysqli_close($conn);
?>
