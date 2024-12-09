<?php
require_once "../config/config.php";

if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];

    // Fetch restaurant name (always)
    $sql = "SELECT restaurant_name FROM RESTAURANTS WHERE restaurant_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if restaurant exists
    if ($row = mysqli_fetch_assoc($result)) {
        $restaurant_name = $row['restaurant_name'];
    } else {
        echo "Restaurant not found.";
        exit;
    }

    // Fetch reservations for the selected restaurant
    $sql_reservations = "SELECT reservation_id, member_name, date, party_size 
                         FROM RESERVATIONS WHERE restaurant_id = ?";
    $stmt_reservations = mysqli_prepare($link, $sql_reservations);
    mysqli_stmt_bind_param($stmt_reservations, "i", $restaurant_id);
    mysqli_stmt_execute($stmt_reservations);
    $result_reservations = mysqli_stmt_get_result($stmt_reservations);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reservations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Reservations for <?php echo htmlspecialchars($restaurant_name); ?></h2>
        <a href="create_reservation.php?restaurant_id=<?php echo $restaurant_id; ?>" class="btn btn-success">Add Reservation</a>
        <a href="index.php" class="btn btn-secondary">View Restaurants</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Member Name</th>
                    <th>Date</th>
                    <th>Party Size</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are any reservations
                if (mysqli_num_rows($result_reservations) > 0) {
                    while ($row = mysqli_fetch_assoc($result_reservations)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['reservation_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['member_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['party_size']) . "</td>";
                        echo "<td><a href='update_reservation.php?reservation_id=" . $row['reservation_id'] . "' class='btn btn-primary'>Update</a></td>";
                        echo "<td><a href='delete_reservation.php?reservation_id=" . $row['reservation_id'] . "&restaurant_id=" . $restaurant_id . "' class='btn btn-danger'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No reservations found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt_reservations);
} else {
    echo "Restaurant ID not provided.";
}

mysqli_close($link);
?>
