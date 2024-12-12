<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


<?php
// Include config file

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "../config/config.php";


// Check if member_id is provided
if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];
} else {
    echo "Member ID is required.";
    exit;
}


// Fetch restaurants
$sql = "SELECT RESTAURANTS.*, 
               OWNER.name AS owner_name, 
               RESTAURANT_LOCATION.State 
        FROM RESTAURANTS
        LEFT JOIN RESTAURANT_LOCATION ON RESTAURANTS.location_id = RESTAURANT_LOCATION.location_id
        LEFT JOIN OWNER ON RESTAURANTS.owner_id = OWNER.owner_id";
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
                    </div>
                    <a href="./read/read_members.php" class="btn btn-info pull-left">View Members</a>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Restaurant</th>
                                <th>Cuisine</th>
                                <th>State</th>
                                <th>Phone Number</th>
                                <th>Owner</th>
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
                                    <td>
                                    <a href="./create/create_reviews.php?restaurant_id=<?php echo $row['restaurant_id']; ?>&member_id=<?php echo $member_id; ?>" class="btn btn-primary">Add Review</a>
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