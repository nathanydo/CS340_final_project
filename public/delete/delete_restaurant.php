<?php

require_once "../../config/config.php";

if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];


    $sql = "DELETE FROM RESTAURANTS WHERE restaurant_id = ?";
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false){
        echo "Error preparing the statement: " . mysqli_error($link);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "i", $restaurant_id);

    if (mysqli_stmt_execute($stmt)) {

        header("Location: ../index.php");
        exit;
    }
    else {
        echo "Error deleting restaurant: " . mysqli_error($link);
    }

    mysqli_stmnt_close($stmt);
}
else {
    echo "Restaurant ID not provided";
}

mysqli_close($link);
?>