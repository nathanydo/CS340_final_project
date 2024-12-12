<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


<?php
// Include config file
require_once "../../config/config.php";

// Check if review_id and member_id are provided
if (isset($_GET['review_id']) && !empty($_GET['review_id']) && isset($_GET['member_id']) && !empty($_GET['member_id'])) {
    $review_id = $_GET['review_id'];
    $member_id = $_GET['member_id'];

    // Prepare the SQL statement to delete the review
    $sql = "DELETE FROM REVIEWS WHERE review_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $review_id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the reviews page for the specific member
            header("Location: ../read/read_reviews.php?member_id=" . $member_id);
            exit();
        } else {
            echo "Error: Could not execute the delete query. Please try again.";
        }
    } else {
        echo "Error: Could not prepare the delete query.";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Error: Invalid or missing review ID or member ID.";
}

// Close the database connection
mysqli_close($link);
?>
