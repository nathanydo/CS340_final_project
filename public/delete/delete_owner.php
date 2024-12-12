<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


<?php
// Include config file
require_once "../../config/config.php";

// Check if the owner_id is provided in the URL
if (isset($_GET['owner_id'])) {
    $owner_id = $_GET['owner_id'];

    // Fetch the owner details to confirm they exist
    $sql = "SELECT * FROM OWNER WHERE owner_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $owner_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $owner = mysqli_fetch_assoc($result);
            } else {
                echo "Owner not found!";
                exit;
            }
        } else {
            echo "Error retrieving owner details.";
            exit;
        }
        mysqli_stmt_close($stmt);
    }

    // Delete the owner record from the OWNER table
    $sql_delete = "DELETE FROM OWNER WHERE owner_id = ?";
    if ($stmt = mysqli_prepare($link, $sql_delete)) {
        mysqli_stmt_bind_param($stmt, "i", $owner_id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../read/read_owners.php"); // Redirect to the owner list page
            exit;
        } else {
            echo "Error deleting owner. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
} else {
    echo "No owner selected for deletion!";
    exit;
}
?>
