<?php
// Include config file
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "../../config/config.php";

if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];

    // Fetch restaurant name
    $sql_restaurant = "SELECT restaurant_name FROM RESTAURANTS WHERE restaurant_id = ?";
    $stmt_restaurant = mysqli_prepare($link, $sql_restaurant);
    mysqli_stmt_bind_param($stmt_restaurant, "i", $restaurant_id);
    mysqli_stmt_execute($stmt_restaurant);
    $result_restaurant = mysqli_stmt_get_result($stmt_restaurant);

    if ($row = mysqli_fetch_assoc($result_restaurant)) {
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12"> 
                    <div class="mt-5 mb-3 clearfix">
                        <h2>Reservations for <?php echo htmlspecialchars($restaurant_name); ?></h2>
                    </div>
                    <a href="../index.php" class="btn btn-secondary">View Restaurants</a>
                    <a href="../create/create_reservation.php?restaurant_id=<?php echo $restaurant_id; ?>" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Reservation</a>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Reservation ID</th>
                                <th>Member Name</th>
                                <th>Date</th>
                                <th>Party Size</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Check if there are any reservations
                            if (mysqli_num_rows($result_reservations) > 0) {
                                while ($row = mysqli_fetch_assoc($result_reservations)) { ?>
                                    <tr>
                                        <td><?php echo $row['reservation_id']; ?> </td>
                                        <td><?php echo $row['member_name']; ?></td>
                                        <td><?php echo $row['date']; ?></td>
                                        <td><?php echo $row['party_size']; ?></td>
                                        <td>
                                            <a href="../update/update_reservations.php?reservation_id=<?php echo $row['reservation_id']; ?>" class="mr-3" title="Update Reservation" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                                            <a href="../delete/delete_reservations.php?reservation_id=<?php echo $row['reservation_id']; ?>" class="mr-3" title="Delete Reservation" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
                                        </td>
                                    </tr>
                                <?php } 
                            } else {
                                echo "<tr><td colspan='5'>No reservations found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
    // Close statements
    mysqli_stmt_close($stmt_restaurant);
    mysqli_stmt_close($stmt_reservations);
} else {
    echo "Restaurant ID not provided.";
}

// Close the connection
mysqli_close($link);
?>
