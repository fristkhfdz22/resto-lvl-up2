<?php
include 'db_connect.php'; // Adjust the path as needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $quantities = $_POST['quantity']; // Array of quantities
    $prices = $_POST['price']; // Array of prices

    $total_price = 0;
    $order_details = [];

    foreach ($quantities as $dish_id => $quantity) {
        if (is_numeric($quantity) && $quantity > 0) {
            $price_per_item = $prices[$dish_id];
            $total_price += $quantity * $price_per_item;
            $order_details[] = [
                'dish_id' => $dish_id,
                'quantity' => $quantity,
                'price_per_item' => $price_per_item,
                'total' => $quantity * $price_per_item
            ];
        }
    }

    // Insert order into orders table
    $sql = "INSERT INTO orders (customer_name, total_price) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'sd', $name, $total_price);
        if (mysqli_stmt_execute($stmt)) {
            $order_id = mysqli_insert_id($conn);

            // Insert order details into order_details table
            foreach ($order_details as $detail) {
                $dish_id = $detail['dish_id'];
                $quantity = $detail['quantity'];
                $price_per_item = $detail['price_per_item'];
                $total = $detail['total'];

                $sql = "INSERT INTO order_details (order_id, dish_id, quantity, price_per_item, total)
                        VALUES (?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, 'iiddd', $order_id, $dish_id, $quantity, $price_per_item, $total);
                    mysqli_stmt_execute($stmt);
                }
            }

            echo "<div class='container mt-4'>";
            echo "<div class='alert alert-success' role='alert'>";
            echo "<h1>Pesanan Diterima</h1>";
            echo "<p>Nama: " . htmlspecialchars($name) . "</p>";
            echo "<p>Total Harga: $" . number_format($total_price, 2) . "</p>";
            echo "</div>";
            echo "<a href='index.php' class='btn btn-primary'>Back to Menu</a>";
            echo "</div>";
        } else {
            echo "<div class='container mt-4'><div class='alert alert-danger' role='alert'>Error: " . mysqli_error($conn) . "</div></div>";
        }
    } else {
        echo "<div class='container mt-4'><div class='alert alert-danger' role='alert'>Error preparing statement: " . mysqli_error($conn) . "</div></div>";
    }
} else {
    echo "<div class='container mt-4'><div class='alert alert-warning' role='alert'>Metode request tidak valid.</div></div>";
}

mysqli_close($conn);
?>
