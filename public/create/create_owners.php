<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->



<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
// Include config file
require_once "../../config/config.php";

// Fetch restaurants for the dropdown
$restaurants_sql = "SELECT restaurant_id, restaurant_name FROM RESTAURANTS";
$restaurants_result = mysqli_query($link, $restaurants_sql);

// Initialize variables and error messages
$name = $phone_number = $email = $restaurant_id = "";
$name_err = $phone_number_err = $email_err = $restaurant_id_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter the owner's name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate phone_number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter the phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter the email address.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate restaurant_id (allowing NULL if no restaurant is selected)
    if (empty($_POST["restaurant_id"])) {
        $restaurant_id = NULL; // Allow NULL if no restaurant is selected
    } else {
        $restaurant_id = trim($_POST["restaurant_id"]);
    }

    // Check for errors before inserting into the database
    if (empty($name_err) && empty($phone_number_err) && empty($email_err)) {
        // Prepare an INSERT statement
        $sql = "INSERT INTO OWNER (name, phone_number, email, restaurant_id) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $name, $phone_number, $email, $restaurant_id);

            if (mysqli_stmt_execute($stmt)) {
                // Redirect to the owners list page
                header("Location: read_owners.php");
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
    <title>Create Owner</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create New Owner</h2>
                    <p>Please fill this form to add a new owner.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Owner Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
                            <span class="invalid-feedback"><?php echo $phone_number_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Restaurant</label>
                            <select name="restaurant_id" class="form-control <?php echo (!empty($restaurant_id_err)) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Restaurant (optional)</option>
                                <?php while ($restaurant = mysqli_fetch_assoc($restaurants_result)) { ?>
                                    <option value="<?php echo $restaurant['restaurant_id']; ?>" <?php echo ($restaurant_id == $restaurant['restaurant_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($restaurant['restaurant_name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $restaurant_id_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="../read/read_owners.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
