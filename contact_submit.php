<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $date = $_POST['date']; // Reservation date
    $time = $_POST['time']; // Reservation time

    $sql = "INSERT INTO contacts (name, email, message, reservation_date, reservation_time) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $message, $date, $time);
        if (mysqli_stmt_execute($stmt)) {
            $notification = "<div class='container mt-4'><div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Berhasil!</strong> Pesan dan Reservasi Anda telah dikirim.
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div></div>";
        } else {
            $notification = "<div class='container mt-4'><div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                <strong>Error!</strong> " . mysqli_error($conn) . "
                                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div></div>";
        }
        mysqli_stmt_close($stmt);
    } else {
        $notification = "<div class='container mt-4'><div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            <strong>Error!</strong> Error preparing statement: " . mysqli_error($conn) . "
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div></div>";
    }
    mysqli_close($conn);
} else {
    $notification = "<div class='container mt-4'><div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>Peringatan!</strong> Invalid request method.
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div></div>";
}

// Include Bootstrap CSS and JS for alerts
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php echo $notification; ?>
    <div class="container mt-4">
        <a href="index.php" class="btn btn-primary">Kembali ke Menu</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
