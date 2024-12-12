<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


<?php
// Include config file
require_once "../../config/config.php";

// Check if member_id is provided
if (isset($_GET['member_id']) && !empty($_GET['member_id'])) {
    $member_id = $_GET['member_id'];

    // Prepare the SQL statement to delete the member
    $sql = "DELETE FROM MEMBERS WHERE member_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $member_id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the members page after successful deletion
            header("Location: ../read/read_members.php");
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
    echo "Error: Invalid or missing member ID.";
}

// Close the database connection
mysqli_close($link);
?>
