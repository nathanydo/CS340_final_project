<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


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

    // Check if party_size filter is set
    $party_size_filter = isset($_GET['party_size']) ? $_GET['party_size'] : '';

    // Build SQL query based on the filter
    $sql_reservations = "SELECT reservation_id, member_name, date, party_size 
                         FROM RESERVATIONS WHERE restaurant_id = ?";

    if ($party_size_filter !== '') {
        // If a party size filter is provided, add it to the query
        $sql_reservations .= " AND party_size = ?";
    }

    $stmt_reservations = mysqli_prepare($link, $sql_reservations);
    if ($party_size_filter !== '') {
        mysqli_stmt_bind_param($stmt_reservations, "ii", $restaurant_id, $party_size_filter);
    } else {
        mysqli_stmt_bind_param($stmt_reservations, "i", $restaurant_id);
    }
    mysqli_stmt_execute($stmt_reservations);
    $result_reservations = mysqli_stmt_get_result($stmt_reservations);

    // Handle the delete of reservations older than a certain date
    if (isset($_POST['delete_reservations']) && isset($_POST['delete_date'])) {
        $delete_date = $_POST['delete_date'];
    
        // Ensure restaurant_id is available from POST
        if (isset($_POST['restaurant_id'])) {
            $restaurant_id = $_POST['restaurant_id']; // Now this is available from the form POST request
    
            // Prepare SQL to delete reservations older than the given date
            $sql_delete_reservations = "DELETE FROM RESERVATIONS WHERE restaurant_id = ? AND date < ?";
            if ($stmt_delete_reservations = mysqli_prepare($link, $sql_delete_reservations)) {
                mysqli_stmt_bind_param($stmt_delete_reservations, "is", $restaurant_id, $delete_date);
    
                if (mysqli_stmt_execute($stmt_delete_reservations)) {
                    echo "<div class='alert alert-success'>Reservations older than $delete_date have been deleted.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error deleting reservations: " . mysqli_error($link) . "</div>";
                }
    
                mysqli_stmt_close($stmt_delete_reservations);
            } else {
                echo "<div class='alert alert-danger'>Error preparing the delete query: " . mysqli_error($link) . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Restaurant ID not provided for deletion.</div>";
        }
    }
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
                    
                    <!-- Filter Form -->
                    <form action="read_reservations.php" method="GET" class="mb-3">
                        <input type="hidden" name="restaurant_id" value="<?php echo $restaurant_id; ?>" />
                        <label for="party_size">Filter by Party Size: </label>
                        <input type="number" name="party_size" id="party_size" class="form-control w-auto d-inline-block" value="<?php echo htmlspecialchars($party_size_filter); ?>" placeholder="Enter party size" min="1" />
                        <button type="submit" class="btn btn-primary mb-1">Filter</button>
                    </form>

                    <form action="read_reservations.php?restaurant_id=<?php echo $restaurant_id; ?>" method="POST" class="mb-3">
                        <!-- Hidden restaurant_id field in POST request -->
                        <input type="hidden" name="restaurant_id" value="<?php echo htmlspecialchars($restaurant_id); ?>" />
                        <label for="delete_date">Delete Reservations Older Than: </label>
                        <input type="date" name="delete_date" id="delete_date" class="form-control w-auto d-inline-block" required />
                        <button type="submit" name="delete_reservations" class="btn btn-danger mb-1">Delete Older Reservations</button>
                    </form>
                    
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
                                            <a href="../update/update_reservations.php?reservation_id=<?php echo $row['reservation_id']; ?>" class="mr-3" title="Update Reservation" data-toggle="tooltip"><span class="fa fa-pencil custom-icon"></span></a>
                                            <a href="../delete/delete_reservations.php?reservation_id=<?php echo $row['reservation_id']; ?>&restaurant_id=<?php echo $restaurant_id; ?>" class="mr-3" title="Delete Reservation" data-toggle="tooltip" onclick="return confirmDelete()"><span class="fa fa-trash custom-icon"></span></a>
                                            <script>
                                                function confirmDelete() {
                                                    return confirm("Are you sure you want to delete this reservation?");
                                                }
                                            </script>
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
