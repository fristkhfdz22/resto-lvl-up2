<?php
// Memulai sesi untuk melacak apakah admin telah login
session_start();

// Memeriksa apakah pengguna telah login; jika tidak, arahkan kembali ke halaman login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Menghubungkan ke database
include __DIR__ . '/../db_connect.php';

// Memeriksa apakah ID item disediakan dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_menu.php");
    exit();
}

// Mendapatkan ID item dari parameter URL
$item_id = $_GET['id'];

// Mengambil detail item dari database berdasarkan ID
$sql = "SELECT * FROM menu_items WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $item_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$item = mysqli_fetch_assoc($result);

// Jika item tidak ditemukan, kembali ke halaman kelola menu
if (!$item) {
    header("Location: admin_menu.php");
    exit();
}

// Menangani pengiriman form untuk memperbarui item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mendapatkan data yang dikirim dari form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Memperbarui detail item di database
    $update_sql = "UPDATE menu_items SET name = ?, description = ?, price = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, 'ssdi', $name, $description, $price, $item_id);
    mysqli_stmt_execute($stmt);

    // Menangani unggahan gambar jika gambar baru disediakan
    if (!empty($image['name'])) {
        $target_dir = __DIR__ . '/../images/';
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file gambar
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if ($image["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Jika file gambar valid, unggah file dan perbarui data gambar di database
        if ($uploadOk == 1) {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                $update_image_sql = "UPDATE menu_items SET image = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $update_image_sql);
                mysqli_stmt_bind_param($stmt, 'si', basename($image["name"]), $item_id);
                mysqli_stmt_execute($stmt);
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Setelah update, kembali ke halaman kelola menu
    header("Location: admin_menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar untuk navigasi admin -->
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
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Edit Menu Item</h1>
        <!-- Form untuk mengedit item menu -->
        <form action="edit_menu.php?id=<?php echo $item_id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($item['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($item['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($item['price']); ?>" required>
            </div>

            <!-- Menampilkan gambar saat ini jika ada -->
            <div class="form-group">
                <label for="image">Image (Leave empty if not changing):</label><br>
                <?php if ($item['image']): ?>
                    <img src="../images/<?php echo htmlspecialchars($item['image']); ?>" alt="Current Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;"><br>
                <?php else: ?>
                    <p>No image available.</p>
                <?php endif; ?>
                <input type="file" id="image" name="image" class="form-control-file">
            </div>

            <button type="submit" class="btn btn-primary">Update Item</button>
        </form>
        <a href="admin_menu.php" class="btn btn-secondary mt-3">Back to Menu Management</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn); // Menutup koneksi database
?>
