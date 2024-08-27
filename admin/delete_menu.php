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

// Mendapatkan ID dari item menu yang akan dihapus dari parameter URL
$id = $_GET['id'];

// Menyiapkan query SQL untuk menghapus item menu berdasarkan ID
$sql = "DELETE FROM menu_items WHERE id='$id'";

// Mengeksekusi query dan memeriksa apakah berhasil
if (mysqli_query($conn, $sql)) {
    // Jika berhasil, arahkan kembali ke halaman kelola menu
    header("Location: admin_menu.php");
    exit();
} else {
    // Jika terjadi kesalahan, tampilkan pesan kesalahan
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
?>
