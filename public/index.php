<?php
// Include config file
require_once "../config/config.php";

// Fetch restaurants
$sql = "SELECT * FROM RESTAURANTS";
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
                        <h2 class="pull-left">Restaurant Reservation App</h2>
                        <a href="create_restaurant.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Restaurant</a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Restaurant</th>
                                <th>Cuisine</th>
                                <th>Location</th>
                                <th>Phone Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                                <tr>
                                    <td><?php echo $row['restaurant_id']; ?></td>
                                    <td><?php echo $row['restaurant_name']; ?></td>
                                    <td><?php echo $row['cuisine']; ?></td>
                                    <td><?php echo $row['location']; ?></td>
                                    <td><?php echo $row['phone_number']; ?></td>
                                    <td>
                                    <a href="read_reservations.php?restaurant_id=<?php echo $row['restaurant_id']; ?>" class="mr-3" title="View Reservations" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
                                    <a href="update_restaurant.php?restaurant_id=<?php echo $row['restaurant_id']; ?>" class="mr-3" title="Update Restaurant" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                                    <a href="delete_restaurant.php?restuarant_id=<?php echo $row['restaurant_id']; ?>" class="mr-3" title="Delete Restaurant" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
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