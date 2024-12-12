<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->



<?php
// Include config file
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "../../config/config.php";

if (isset($_GET['restaurant_id']) && isset($_GET['member_id'])) {
    $restaurant_id = $_GET['restaurant_id'];
    $member_id = $_GET['member_id'];

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

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $review = $_POST['review'];

        // Insert the review into the database
        $sql_insert = "INSERT INTO REVIEWS (member_id, restaurant_id, review) VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($link, $sql_insert);
        mysqli_stmt_bind_param($stmt_insert, "iii", $member_id, $restaurant_id, $review);

        if (mysqli_stmt_execute($stmt_insert)) {
            echo "<script>alert('Review submitted successfully!'); window.location.href='../reviews.php?member_id=$member_id';</script>";
        } else {
            echo "Error: Could not submit the review.";
        }
    }
} else {
    echo "Restaurant ID or Member ID not provided.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Restaurant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3">
                        <h2>Review <?php echo htmlspecialchars($restaurant_name); ?></h2>
                    </div>
                    <form action="create_reviews.php?restaurant_id=<?php echo $restaurant_id; ?>&member_id=<?php echo $member_id; ?>" method="post">
                        <div class="form-group">
                            <label for="review">Rating (1-5):</label>
                            <select id="review" name="review" class="form-control" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                        <a href="../reviews.php?member_id=<?php echo $member_id; ?>" class="btn btn-secondary">Cancel</a>
                    </form>
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
