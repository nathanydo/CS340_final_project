<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


<?php
// Include config file
require_once "../../config/config.php";

// Initialize variables
$restaurant_name = null;
$member_name = null;
$is_user_reviews = false;

// Check if member_id or restaurant_id is passed
if (isset($_GET['member_id']) && !isset($_GET['restaurant_id'])) {
    // Fetch reviews for a specific user
    $member_id = $_GET['member_id'];
    $is_user_reviews = true;

    // Fetch member details
    $sql_member = "SELECT username FROM MEMBERS WHERE member_id = ?";
    $stmt_member = mysqli_prepare($link, $sql_member);
    mysqli_stmt_bind_param($stmt_member, "i", $member_id);
    mysqli_stmt_execute($stmt_member);
    $result_member = mysqli_stmt_get_result($stmt_member);

    if ($row = mysqli_fetch_assoc($result_member)) {
        $member_name = $row['username'];
    } else {
        echo "Member not found.";
        exit;
    }

    // Fetch reviews created by this user
    $sql_reviews = "SELECT REVIEWS.review_id, REVIEWS.review, RESTAURANTS.restaurant_name 
                    FROM REVIEWS
                    LEFT JOIN RESTAURANTS ON REVIEWS.restaurant_id = RESTAURANTS.restaurant_id
                    WHERE REVIEWS.member_id = ?";
    $stmt_reviews = mysqli_prepare($link, $sql_reviews);
    mysqli_stmt_bind_param($stmt_reviews, "i", $member_id);
    mysqli_stmt_execute($stmt_reviews);
    $result_reviews = mysqli_stmt_get_result($stmt_reviews);

} elseif (isset($_GET['restaurant_id'])) {
    // Fetch reviews for a specific restaurant
    $restaurant_id = $_GET['restaurant_id'];

    // Fetch restaurant details
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

    // Fetch reviews for this restaurant
    $sql_reviews = "SELECT REVIEWS.review_id, REVIEWS.review, MEMBERS.username 
                    FROM REVIEWS
                    LEFT JOIN MEMBERS ON REVIEWS.member_id = MEMBERS.member_id
                    WHERE REVIEWS.restaurant_id = ?";
    $stmt_reviews = mysqli_prepare($link, $sql_reviews);
    mysqli_stmt_bind_param($stmt_reviews, "i", $restaurant_id);
    mysqli_stmt_execute($stmt_reviews);
    $result_reviews = mysqli_stmt_get_result($stmt_reviews);

} else {
    echo "No valid parameters provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $is_user_reviews ? "User's Reviews" : "Restaurant Reviews"; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">
                            <?php
                            if ($is_user_reviews) {
                                echo "Reviews by " . htmlspecialchars($member_name);
                            } else {
                                echo "Reviews for " . htmlspecialchars($restaurant_name);
                            }
                            ?>
                        </h2>
                        <?php if ($is_user_reviews) { ?>
                            <a href="./read_members" class="btn btn-secondary pull-right">Back to Members</a>
                        <?php } else { ?>
                            <a href="../index.php" class="btn btn-secondary pull-right">Back to Restaurants</a>
                        <?php } ?>
                    </div>
                    <?php if (mysqli_num_rows($result_reviews) > 0) { ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo $is_user_reviews ? "Restaurant" : "User"; ?></th>
                                    <th>Rating</th>
                                    <?php if ($is_user_reviews) { ?>
                                        <th>Actions</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result_reviews)) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $is_user_reviews 
                                                ? htmlspecialchars($row['restaurant_name']) 
                                                : htmlspecialchars($row['username']); ?>
                                        </td>
                                        <td><?php echo $row['review']; ?></td>
                                        <?php if ($is_user_reviews) { ?>
                                            <td>
                                                <a href="../update/update_reviews.php?review_id=<?php echo $row['review_id']; ?>&member_id=<?php echo $member_id; ?>" class="btn btn-warning btn-sm">Update</a>
                                                <a href="../delete/delete_reviews.php?review_id=<?php echo $row['review_id']; ?>&member_id=<?php echo $_GET['member_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete()">Delete</a>
                                                <script>
                                                function confirmDelete() {
                                                    return confirm("Are you sure you want to delete this review?");
                                                }
                                                </script>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p class="text-muted">No reviews found.</p>
                    <?php } ?>
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
