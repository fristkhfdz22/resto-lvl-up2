<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../db_connect.php';

// Handle deletion if 'delete' parameter is set
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // First, delete related order details
    $delete_details_sql = "DELETE FROM order_details WHERE order_id = ?";
    $stmt = mysqli_prepare($conn, $delete_details_sql);
    mysqli_stmt_bind_param($stmt, 'i', $delete_id);
    mysqli_stmt_execute($stmt);

    // Then, delete the order
    $delete_sql = "DELETE FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt, 'i', $delete_id);
    if (mysqli_stmt_execute($stmt)) {
        $message = "<div class='alert alert-success' role='alert'>Order deleted successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Error deleting order: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch orders
$sql = "SELECT * FROM orders";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                    <a class="nav-link" href="admin_panel.php">contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Manage Orders</h1>

        <!-- Display the notification if exists -->
        <?php if (isset($message)) echo $message; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Price</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                    <td>
                        <a href="view_order_details.php?id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-info btn-sm">View Details</a>
                    </td>
                    <td>
                        <a href="?delete_id=<?php echo htmlspecialchars($order['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Automatically hide alert messages after 5 seconds
        window.addEventListener('DOMContentLoaded', (event) => {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 600); // Wait for fade-out before removing element
                }, 5000); // Hide after 5 seconds
            }
        });
    </script>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
