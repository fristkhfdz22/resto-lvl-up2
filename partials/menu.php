
                <!-- Item Menu 1 -->
                <?php
include 'db_connect.php';

// Fetch menu items from the database
$sql = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $sql);
?>

<section id="menu" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Menu Kami</h2>
        <form action="order.php" method="post">
            <div class="row">
                <?php while ($item = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="images/<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p class="card-price">$<?php echo number_format($item['price'], 2); ?></p>
                            <div class="form-group">
                                <label for="quantity<?php echo $item['id']; ?>">Jumlah:</label>
                                <input type="number" id="quantity<?php echo $item['id']; ?>" name="quantity[<?php echo $item['id']; ?>]" min="0" value="0" class="form-control">
                                <input type="hidden" name="price[<?php echo $item['id']; ?>]" value="<?php echo htmlspecialchars($item['price']); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <!-- Input untuk Nama Pelanggan -->
            <div class="form-group mt-4">
                <label for="customerName">Nama Anda:</label>
                <input type="text" id="customerName" name="name" class="form-control" required>
            </div>

            <!-- Button untuk Submit -->
            <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
        </form>
    </div>
</section>

<?php
mysqli_close($conn); // Close the database connection
?>


