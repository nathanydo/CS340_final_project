<?php
// Include config file
require_once "../../config/config.php";

// Check if the owner_id is provided in the URL
if (isset($_GET['owner_id'])) {
    $owner_id = $_GET['owner_id'];

    // Fetch the owner details
    $sql = "SELECT * FROM OWNER WHERE owner_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $owner_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $owner = mysqli_fetch_assoc($result);
                $restaurant_id = $owner['restaurant_id']; // Get the associated restaurant_id
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
} else {
    echo "No owner selected!";
    exit;
}

// Fetch all restaurants (for the dropdown in case of updating the restaurant association)
$sql_restaurants = "SELECT restaurant_id, restaurant_name FROM RESTAURANTS";
$restaurants_result = mysqli_query($link, $sql_restaurants);
if (!$restaurants_result) {
    echo "Error fetching restaurants: " . mysqli_error($link);
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $restaurant_id = $_POST['restaurant_id'];  // New restaurant association

    // Update the owner details
    $sql = "UPDATE OWNER SET name = ?, email = ?, phone_number = ?, restaurant_id = ? WHERE owner_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssii", $name, $email, $phone_number, $restaurant_id, $owner_id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../read/read_owners.php"); // Redirect to the owner list page
            exit;
        } else {
            echo "Error updating owner. Please try again.";
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
    <title>Update Owner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Update Owner</h2>
        <form action="../update/update_owner.php?owner_id=<?php echo $owner_id; ?>" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($owner['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($owner['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($owner['phone_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="restaurant_id">Restaurant:</label>
                <select name="restaurant_id" class="form-control" required>
                    <option value="">Select Restaurant</option>
                    <?php while ($restaurant = mysqli_fetch_assoc($restaurants_result)) { ?>
                        <option value="<?php echo $restaurant['restaurant_id']; ?>" <?php echo ($restaurant_id == $restaurant['restaurant_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($restaurant['restaurant_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="../read/read_owners.php" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</body>
</html>