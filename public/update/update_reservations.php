<?php
require_once "../../config/config.php";

// Fetch the reservation details
if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];

    // Retrieve current reservation data, including restaurant_id
    $sql = "SELECT member_name, party_size, date, restaurant_id FROM RESERVATIONS WHERE reservation_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $reservation_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $member_name = $row['member_name'];
        $party_size = $row['party_size'];
        $date = $row['date'];
        $restaurant_id = $row['restaurant_id']; // Retrieve restaurant_id
    } else {
        echo "Reservation not found.";
        exit;
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Reservation ID not provided.";
    exit;
}

// Handle form submission for updating reservation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_name = $_POST['member_name'];
    $party_size = $_POST['party_size'];
    $date = $_POST['date'];

    // Update reservation in the database
    $sql = "UPDATE RESERVATIONS SET member_name = ?, date = ?, party_size = ? WHERE reservation_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $member_name, $date, $party_size, $reservation_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirect to the reservations page
        header("Location: ../read/read_reservations.php?restaurant_id=" . $restaurant_id); // Use retrieved restaurant_id
        exit;
    } else {
        echo "Error updating reservation: " . mysqli_error($link);
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="container">
        <h2>Update Reservation</h2>
        <form action="../update/update_reservation.php?reservation_id=<?php echo $reservation_id; ?>&restaurant_id=<?php echo $_GET['restaurant_id']; ?>" method="POST">
            <div class="form-group">
                <label for="member_name">Member Name:</label>
                <input type="text" class="form-control" id="member_name" name="member_name" value="<?php echo htmlspecialchars($member_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="party_size">Party Size:</label>
                <input type="number" class="form-control" id="party_size" name="party_size" value="<?php echo htmlspecialchars($party_size); ?>" required>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="../read/read_reservations.php?restaurant_id=<?php echo $restaurant_id; ?>" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</body>
</html>
