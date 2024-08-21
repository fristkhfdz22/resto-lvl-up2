<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    // Handle file upload
    $image = $_FILES['image']['name'];
    $target_dir = "../images/";
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (5MB max)
    if ($_FILES['image']['size'] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insert record into the database
            $sql = "INSERT INTO menu_items (name, description, price, image) VALUES ('$name', '$description', '$price', '$image')";
            if (mysqli_query($conn, $sql)) {
                header("Location: admin_menu.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Menu Item</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h1>Add New Menu Item</h1>
    <form action="add_menu.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required><br>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" required><br>
        <button type="submit">Add Item</button>
    </form>
    <a href="admin_menu.php">Back to Menu Management</a>
</body>
</html>
