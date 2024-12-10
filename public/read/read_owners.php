<?php
// Include config file


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "../../config/config.php";

// Fetch restaurants
$sql = "SELECT OWNER.owner_id, OWNER.name, OWNER.email, OWNER.phone_number, 
RESTAURANTS.restaurant_name 
FROM OWNER 
LEFT JOIN RESTAURANTS 
ON OWNER.restaurant_id = RESTAURANTS.restaurant_id";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo "Error fetching data: " . mysqli_error($link);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Reservation App</title>
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
                        <h2 class="pull-left">The Big Backs</h2> 
                    </div>
                    <p>Owner Page</p>
                    <div>
                        <a href="../index.php" class="btn btn-secondary pull-left">View Restaurants</a>
                        <a href="../create/create_owners.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Owner</a>  
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Owner ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Restaurant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                                <tr>
                                    <td><?php echo $row['owner_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone_number']; ?></td>
                                    <td><?php 
                                            // Show restaurant name if exists, otherwise "No Restaurant Assigned"
                                            echo !empty($row['restaurant_name']) ? htmlspecialchars($row['restaurant_name']) : "No Restaurant Assigned"; 
                                            ?></td>
                                    <td>
                                    <a href="../update/update_owner.php?owner_id=<?php echo $row['owner_id']; ?>" class="mr-3" title="Update Owner" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                                    <a href="../delete/delete_owner.php?owner_id=<?php echo $row['owner_id']; ?>" class="mr-3" title="Delete Owner" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    </div>
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