<?php
// Menyertakan file koneksi database
include 'db_connect.php';

// Mengambil item menu dari database
$sql = "SELECT * FROM menu_items";
$result = mysqli_query($conn, $sql);
?>

<section id="menu" class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Menu Kami</h2>
        <!-- Formulir untuk memesan -->
        <form action="order.php" method="post">
            <div class="row">
                <?php while ($item = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4">
                    <div class="card">
                        <!-- Menampilkan gambar item menu -->
                        <img src="images/<?php echo htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['name']); ?>">
                        <div class="card-body">
                            <!-- Menampilkan nama item menu -->
                            <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                            <!-- Menampilkan deskripsi item menu -->
                            <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                            <!-- Menampilkan harga item menu -->
                            <p class="card-price">Rp<?php echo number_format($item['price'],2); ?></p>
                            <div class="form-group">
                                <!-- Input untuk jumlah item menu yang akan dipesan -->
                                <input type="number" id="quantity<?php echo $item['id']; ?>" name="quantity[<?php echo $item['id']; ?>]" min="0" value="0" class="form-control">
                                <!-- Input tersembunyi untuk harga item menu -->
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

            <!-- Tombol untuk submit formulir -->
            <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
        </form>
    </div>
</section>

<?php
// Menutup koneksi database
mysqli_close($conn);
?>
