<?php
// Include config file

require_once "../../config/config.php";

// Fetch restaurants
$sql = "SELECT * FROM MEMBERS";
$result = mysqli_query($link, $sql);
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
                    <p>Member Page</p>
                    <div>
                    <a href="../index.php" class="btn btn-secondary pull-left">View Restaurants</a>
                    <a href="../create/create_members.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Member</a>  
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Date of Birth</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_array($result)) { ?>
                                <tr>
                                    <td><?php echo $row['member_id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['dob']; ?></td>
                                    <td>
                                    <a href="../update/update_member.php?member_id=<?php echo $row['member_id']; ?>" class="mr-3" title="Update Member" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
                                    <a href="../delete/delete_member.php?member_id=<?php echo $row['member_id']; ?>" class="mr-3" title="Delete Member" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
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