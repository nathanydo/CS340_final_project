<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->



<?php
require_once "../../config/config.php";

// Check if restaurant_id is provided
if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];
} else {
    echo "Restaurant ID not provided.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data and insert into the database
    $member_name = $_POST['member_name'];
    $party_size = $_POST['party_size'];
    $reservation_date = $_POST['date'];

    // Insert the reservation into the database
    $sql = "INSERT INTO RESERVATIONS (restaurant_id, member_name, date, party_size) 
        VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "issi", $restaurant_id, $member_name, $reservation_date, $party_size);

    if (mysqli_stmt_execute($stmt)) {
        echo "Reservation added successfully!";
        header("Location: ../read/read_reservations.php?restaurant_id=" . $restaurant_id);
    } else {
        echo "Something went wrong. Please try again.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="container">
        <h2>Add a Reservation</h2>
        <form action="../create/create_reservation.php?restaurant_id=<?php echo $restaurant_id; ?>" method="POST">
            <div class="form-group">
                <label for="member_name">Member Name:</label>
                <input type="text" class="form-control" id="member_name" name="member_name" required>
            </div>
            <div class="form-group">
                <label for="party_size">Party Size:</label>
                <input type="number" class="form-control" id="party_size" name="party_size" required>
            </div>
            <div class="form-group">
                <label for="reservation_date">Reservation Date:</label>
                <input type="date" class="form-control" id="reservation_date" name="date" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="../read/read_reservations.php?restaurant_id=<?php echo $restaurant_id; ?>" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</body>
</html>