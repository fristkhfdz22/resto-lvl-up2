<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../db_connect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM order_details WHERE order_id='$id'";
$result = mysqli_query($conn, $sql);

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
        <h1 class="mb-4">Order Details</h1>
        <h2 class="mb-3">Order ID: <?php echo htmlspecialchars($id); ?></h2>
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
        <p><strong>Total Price:</strong> $<?php echo number_format($order['total_price'], 2); ?></p>

        <table class="table table-striped">
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
                    <td>$<?php echo number_format($detail['price_per_item'], 2); ?></td>
                    <td>$<?php echo number_format($detail['total'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="admin_orders.php" class="btn btn-secondary">Back to Orders</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn); // Close the database connection
?>
