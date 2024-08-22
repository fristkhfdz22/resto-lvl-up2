<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <style>/* General Body Styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Container Styling */
.container {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
}

/* Alert Styling */
.alert {
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeeba;
}

/* Button Styling */
.btn {
    display: inline-block;
    font-weight: 400;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background-color: #007bff;
    border: 1px solid #007bff;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3;
    border-color: #004085;
}

/* Headings */
h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: #333;
}

/* Paragraphs */
p {
    font-size: 1.1rem;
    margin-bottom: 10px;
}

/* Back to Menu Button */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}
</style>
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

</body>
</html>