<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


<?php
// Include config file
require_once "../../config/config.php";

if (isset($_GET['review_id'])) {
    $review_id = $_GET['review_id'];

    // Fetch the current review details
    $sql_review = "SELECT review FROM REVIEWS WHERE review_id = ?";
    $stmt_review = mysqli_prepare($link, $sql_review);
    mysqli_stmt_bind_param($stmt_review, "i", $review_id);
    mysqli_stmt_execute($stmt_review);
    $result_review = mysqli_stmt_get_result($stmt_review);

    if ($row = mysqli_fetch_assoc($result_review)) {
        $current_review = $row['review'];
    } else {
        echo "Review not found.";
        exit;
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_review = $_POST['review'];

        // Update the review in the database
        $sql_update = "UPDATE REVIEWS SET review = ? WHERE review_id = ?";
        $stmt_update = mysqli_prepare($link, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "ii", $new_review, $review_id);

        if (mysqli_stmt_execute($stmt_update)) {
            header("Location: ../read/read_reviews.php?member_id=" . $_GET['member_id']);
            exit;
        } else {
            echo "Error updating review.";
        }
    }
} else {
    echo "No review ID provided.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Review</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Review</h2>
                    <form action="../update/update_reviews.php?review_id=<?php echo $review_id; ?>&member_id=<?php echo $_GET['member_id']; ?>" method="post">
                        <div class="form-group">
                            <label>Rating (1-5):</label>
                            <select name="review" class="form-control">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($i == $current_review) ? "selected" : ""; ?>>
                                        <?php echo $i; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="../read/read_reviews.php?member_id=<?php echo $_GET['member_id']; ?>" class="btn btn-secondary">Cancel</a>
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
