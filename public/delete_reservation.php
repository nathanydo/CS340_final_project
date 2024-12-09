<?php
require_once "../config/config.php";

// Check if reservation_id and restaurant_id are provided
if (isset($_GET['reservation_id']) && isset($_GET['restaurant_id'])) {
    $reservation_id = $_GET['reservation_id'];
    $restaurant_id = $_GET['restaurant_id'];

    // Prepare DELETE statement
    $sql = "DELETE FROM RESERVATIONS WHERE reservation_id = ?";
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        echo "Error preparing the statement: " . mysqli_error($link);
        exit;
    }

    // Bind parameters and execute
    mysqli_stmt_bind_param($stmt, "i", $reservation_id);

    if (mysqli_stmt_execute($stmt)) {
        // If deletion is successful, redirect to the reservations page
        header("Location: read_reservations.php?restaurant_id=" . $restaurant_id);
        exit;
    } else {
        echo "Error deleting reservation: " . mysqli_error($link);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Reservation ID or Restaurant ID not provided.";
}

// Close the database connection
mysqli_close($link);
?>