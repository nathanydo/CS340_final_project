<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include config file
require_once "../../config/config.php";

// Fetch owners for the dropdown
$owners_sql = "SELECT owner_id, name FROM OWNER";
$owners_result = mysqli_query($link, $owners_sql);

// Initialize variables and error messages
$restaurant_name = $cuisine = $location = $phone_number = $owner_id = "";
$restaurant_name_err = $cuisine_err = $location_err = $phone_number_err = $owner_id_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate restaurant_name
    if (empty(trim($_POST["restaurant_name"]))) {
        $restaurant_name_err = "Please enter the restaurant name.";
    } else {
        $restaurant_name = trim($_POST["restaurant_name"]);
    }

    // Validate cuisine
    if (empty(trim($_POST["cuisine"]))) {
        $cuisine_err = "Please enter the cuisine.";
    } else {
        $cuisine = trim($_POST["cuisine"]);
    }

    // Validate location
    if (empty(trim($_POST["location"]))) {
        $location_err = "Please enter the location.";
    } else {
        $location = trim($_POST["location"]);
    }

    // Validate phone_number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter the phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate owner_id
    if (empty($_POST["owner_id"])) {
        $owner_id = NULL;
    } else {
        $owner_id = trim($_POST["owner_id"]);
    }

    // Check for errors before inserting into the database
    if (empty($restaurant_name_err) && empty($cuisine_err) && empty($location_err) && empty($phone_number_err) && empty($owner_id_err)) {
        // Prepare an INSERT statement
        $sql = "INSERT INTO RESTAURANTS (restaurant_name, cuisine, location, phone_number, owner_id) VALUES (?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $restaurant_name, $cuisine, $location, $phone_number, $owner_id);
            
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the main page
                header("Location: index.php");
                exit;
            } else {
                echo "Error: " . mysqli_error($link);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        }
    }
}

// Close the connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Restaurant</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create New Restaurant</h2>
                    <p>Please fill this form to add a new restaurant.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Restaurant Name</label>
                            <input type="text" name="restaurant_name" class="form-control <?php echo (!empty($restaurant_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $restaurant_name; ?>">
                            <span class="invalid-feedback"><?php echo $restaurant_name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Cuisine</label>
                            <input type="text" name="cuisine" class="form-control <?php echo (!empty($cuisine_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cuisine; ?>">
                            <span class="invalid-feedback"><?php echo $cuisine_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control <?php echo (!empty($location_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $location; ?>">
                            <span class="invalid-feedback"><?php echo $location_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
                            <span class="invalid-feedback"><?php echo $phone_number_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Owner</label>
                            <select name="owner_id" class="form-control <?php echo (!empty($owner_id_err)) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Owner (optional)</option>
                                <?php while ($owner = mysqli_fetch_assoc($owners_result)) { ?>
                                    <option value="<?php echo $owner['owner_id']; ?>" <?php echo ($owner_id == $owner['owner_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($owner['name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $owner_id_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
