<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


<?php
// Include config file
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "../config/config.php";


// SQL query to fetch restaurants along with reservation count and review count
$sql = "SELECT RESTAURANTS.*, 
               OWNER.name AS owner_name, 
               RESTAURANT_LOCATION.State, 
               COUNT(RESERVATIONS.reservation_id) AS reservation_count, 
               AVG(REVIEWS.review) AS average_rating  -- Calculate the average rating from the REVIEWS table
        FROM RESTAURANTS
        LEFT JOIN OWNER ON RESTAURANTS.owner_id = OWNER.owner_id
        LEFT JOIN RESTAURANT_LOCATION ON RESTAURANTS.location_id = RESTAURANT_LOCATION.location_id
        LEFT JOIN RESERVATIONS ON RESTAURANTS.restaurant_id = RESERVATIONS.restaurant_id
        LEFT JOIN REVIEWS ON RESTAURANTS.restaurant_id = REVIEWS.restaurant_id
        GROUP BY RESTAURANTS.restaurant_id, RESTAURANTS.restaurant_name, RESTAURANTS.cuisine, RESTAURANTS.phone_number,
        OWNER.name, RESTAURANT_LOCATION.State";

// If the button to sort by reservations is clicked, order by reservation count
if (isset($_GET['sort_by_reservations'])) {
    $sql .= " ORDER BY reservation_count " . ($_GET['sort_by_reservations'] === 'desc' ? "DESC" : "ASC");
} elseif (isset($_GET['sort_by_ratings'])) {
    $sql .= " ORDER BY average_rating " . ($_GET['sort_by_ratings'] === 'desc' ? "DESC" : "ASC");
} else {
    $sql .= " ORDER BY reservation_count ASC"; // Default sorting
}

$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Reservation App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">The Big Backs</h2>
                    <a href="./create/create_restaurant.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Restaurant</a>  
                    <a href="./read/read_members.php" class="btn btn-info pull-left">View Members</a>
                    <a href="./read/read_owners.php" class="btn btn-info pull-left">View Owners</a>
                    </div>
                    <div class="sort_buttons">
                        <div class="sort_reserve">
                        <!-- Sort by reservations button -->
                        <a href="?sort_by_reservations=asc" class="btn btn-primary pull-left">Sort by Reservations (Ascending)</a>
                        <a href="?sort_by_reservations=desc" class="btn btn-primary pull-left">Sort by Reservations (Descending)</a>
                        <a href="?sort_by_ratings=asc" class="btn btn-primary">Sort by Ratings(Ascending)</a>
                        <a href="?sort_by_ratings=desc" class="btn btn-primary">Sort by Ratings(Descending)</a>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Restaurant</th>
                                <th>Cuisine</th>
                                <th>State</th>
                                <th>Phone Number</th>
                                <th>Owner</th>
                                <th>Rating</th> <!-- New column for review count -->
                                <th>Reservations</th> <!-- New column for reservation count -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                                <tr>
                                    <td><?php echo $row['restaurant_id']; ?></td>
                                    <td><?php echo $row['restaurant_name']; ?></td>
                                    <td><?php echo $row['cuisine']; ?></td>
                                    <td><?php echo $row['State']; ?></td>
                                    <td><?php echo $row['phone_number']; ?></td>
                                    <td><?php echo $row['owner_name']; ?></td>
                                    <td> <?php 
                                        // Check if the average rating is null, and set it to 0 if so
                                        $average_rating = $row['average_rating'] !== null ? number_format($row['average_rating'], 2) : '0.00'; 
                                        echo $average_rating;
                                        ?>
                                    </td> <!-- Displaying average rating -->
                                    <td><?php echo $row['reservation_count']; ?></td> <!-- Displaying reservation count -->
                                    <td>
                                        <a href="./read/read_reservations.php?restaurant_id=<?php echo $row['restaurant_id']; ?>" class="mr-3" title="View Reservations" data-toggle="tooltip"><span class="fa fa-eye custom-icon"></span></a>
                                        <a href="./update/update_restaurant.php?restaurant_id=<?php echo $row['restaurant_id']; ?>" class="mr-3" title="Update Restaurant" data-toggle="tooltip" ><span class="fa fa-pencil custom-icon"></span></a>
                                        <a href="./delete/delete_restaurant.php?restaurant_id=<?php echo $row['restaurant_id']; ?>" class="mr-3" title="Delete Restaurant" data-toggle="tooltip" onclick="return confirmDelete()"><span class="fa fa-trash custom-icon"></span></a>
                                        <a href="./read/read_reviews.php?restaurant_id=<?php echo $row['restaurant_id']; ?>" class="mr-3" title="View Reviews" data-toggle="tooltip"><span class="fa fa-comment custom-icon"></span></a>
                                        <script>
                                        function confirmDelete() {
                                            return confirm("Are you sure you want to delete this restaurant?");
                                        }
                                    </script>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>

<?php
// Close connection
mysqli_close($link);
?>
