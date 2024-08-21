<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../db_connect.php';

$id = $_GET['id'];

$sql = "DELETE FROM menu_items WHERE id='$id'";
if (mysqli_query($conn, $sql)) {
    header("Location: admin_menu.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
?>
