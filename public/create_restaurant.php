<?php
// Include the database config
require_once "../config/config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $restaurant_name = $_POST['restaurant_name'];
    $cuisine = $_POST['cuisine'];
    $location = $_POST['location'];
    $phone_number = $_POST['phone_number'];

    // Insert the restaurant into the database
    $sql = "INSERT INTO RESTAURANTS (restaurant_name, cuisine, location, phone_number) 
            VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssss", $restaurant_name, $cuisine, $location, $phone_number);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo "<p>Restaurant added successfully!</p>";
            header("Location: index.php"); // Redirect to the list of restaurants
            exit;
        } else {
            echo "<p>Error: Could not add restaurant. Please try again.</p>";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Restaurant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Add a New Restaurant</h2>
        <form action="create_restaurant.php" method="POST">
            <div class="form-group">
                <label for="restaurant_name">Restaurant Name:</label>
                <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" required>
            </div>
            <div class="form-group">
                <label for="cuisine">Cuisine:</label>
                <input type="text" class="form-control" id="cuisine" name="cuisine" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Restaurant</button>
        </form>
    </div>
</body>
</html>