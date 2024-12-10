<?php
// Include config file
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

require_once "../../config/config.php";

// Check if the restaurant_id is provided in the URL
if (isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];

    // Fetch the restaurant details
    $sql = "SELECT * FROM RESTAURANTS WHERE restaurant_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $restaurant = mysqli_fetch_assoc($result);
                $current_owner_id = $restaurant['owner_id']; // Save current owner_id
            } else {
                echo "Restaurant not found!";
                exit;
            }
        } else {
            echo "Error retrieving restaurant details.";
            exit;
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo "No restaurant selected!";
    exit;
}

// Fetch owners for the dropdown
$sql_owners = "SELECT owner_id, name FROM OWNER";
$owners_result = mysqli_query($link, $sql_owners);
if (!$owners_result) {
    echo "Error fetching owners: " . mysqli_error($link);
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurant_name = $_POST['restaurant_name'];
    $cuisine = $_POST['cuisine'];
    $location = $_POST['State'];
    $phone_number = $_POST['phone_number'];
    $owner_id = $_POST['owner_id'];

    // Update the restaurant details
    $sql_update_restaurant = "UPDATE RESTAURANTS SET restaurant_name = ?, cuisine = ?, State = ?, phone_number = ?, owner_id = ? WHERE restaurant_id = ?";
    if ($stmt = mysqli_prepare($link, $sql_update_restaurant)) {
        mysqli_stmt_bind_param($stmt, "ssssii", $restaurant_name, $cuisine, $location, $phone_number, $owner_id, $restaurant_id);
        if (mysqli_stmt_execute($stmt)) {

            // Check if the owner has changed
            if ($current_owner_id != $owner_id) {
                // Nullify the restaurant_id for the previous owner
                $sql_nullify_old_owner = "UPDATE OWNER SET restaurant_id = NULL WHERE owner_id = ?";
                if ($stmt_nullify = mysqli_prepare($link, $sql_nullify_old_owner)) {
                    mysqli_stmt_bind_param($stmt_nullify, "i", $current_owner_id);
                    mysqli_stmt_execute($stmt_nullify);
                    mysqli_stmt_close($stmt_nullify);
                } else {
                    echo "Error nullifying previous owner's restaurant ID.";
                    exit;
                }
            }

            // Now update the OWNER table to link the correct owner to the restaurant
            $sql_update_owner = "UPDATE OWNER SET restaurant_id = ? WHERE owner_id = ?";
            if ($stmt_owner = mysqli_prepare($link, $sql_update_owner)) {
                mysqli_stmt_bind_param($stmt_owner, "ii", $restaurant_id, $owner_id);
                if (mysqli_stmt_execute($stmt_owner)) {
                    echo "<p>Restaurant and Owner updated successfully!</p>";
                    header("Location: ../index.php"); // Redirect to the restaurant list page
                    exit;
                } else {
                    echo "<p>Error updating owner.</p>";
                }
                mysqli_stmt_close($stmt_owner);
            }
        } else {
            echo "Error updating restaurant.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Restaurant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Update Restaurant</h2>
        <form action="../update/update_restaurant.php?restaurant_id=<?php echo $restaurant_id; ?>" method="POST">
            <div class="form-group">
                <label for="restaurant_name">Restaurant Name:</label>
                <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" value="<?php echo htmlspecialchars($restaurant['restaurant_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="cuisine">Cuisine:</label>
                <input type="text" class="form-control" id="cuisine" name="cuisine" value="<?php echo htmlspecialchars($restaurant['cuisine']); ?>" required>
            </div>
            <div class="form-group">
                <label for="State">Location:</label>
                <input type="text" class="form-control" id="State" name="State" value="<?php echo htmlspecialchars($restaurant['State']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($restaurant['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="owner_id">Owner:</label>
                <select name="owner_id" class="form-control" required>
                    <option value="">Select Owner</option>
                    <?php while ($owner = mysqli_fetch_assoc($owners_result)) { ?>
                        <option value="<?php echo $owner['owner_id']; ?>" <?php echo ($current_owner_id == $owner['owner_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($owner['name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="../index.php" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</body>
</html>
