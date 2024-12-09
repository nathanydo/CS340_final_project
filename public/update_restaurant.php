<?php
// Include config file
require_once "../config/config.php";

// Check if the restaurant_id is provided in the URL
if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];

    // Fetch the restaurant details from the database
    $sql = "SELECT * FROM RESTAURANTS WHERE restaurant_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
        
        // Execute the query and fetch the result
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                // Restaurant found, fetch the data
                $restaurant = mysqli_fetch_assoc($result);
            } else {
                echo "Restaurant not found!";
                exit;
            }
        } else {
            echo "Error retrieving restaurant details.";
            exit;
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }
} else {
    echo "No restaurant selected!";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the form data
    $restaurant_name = $_POST['restaurant_name'];
    $cuisine = $_POST['cuisine'];
    $location = $_POST['location'];
    $phone_number = $_POST['phone_number'];

    // Update the restaurant details in the database
    $sql = "UPDATE RESTAURANTS SET restaurant_name = ?, cuisine = ?, location = ?, phone_number = ? WHERE restaurant_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssssi", $restaurant_name, $cuisine, $location, $phone_number, $restaurant_id);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo "Restaurant updated successfully!";
            header("Location: index.php"); // Redirect to the restaurant list page
            exit;
        } else {
            echo "Error updating restaurant. Please try again.";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Restaurant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Update Restaurant</h2>
        <form action="update_restaurant.php?restaurant_id=<?php echo $restaurant_id; ?>" method="POST">
            <div class="form-group">
                <label for="restaurant_name">Restaurant Name:</label>
                <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" value="<?php echo htmlspecialchars($restaurant['restaurant_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="cuisine">Cuisine:</label>
                <input type="text" class="form-control" id="cuisine" name="cuisine" value="<?php echo htmlspecialchars($restaurant['cuisine']); ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($restaurant['location']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($restaurant['phone_number']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Restaurant</button>
        </form>
    </div>
</body>
</html>