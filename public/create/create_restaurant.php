<!-- CS340 Final Database Project
 
Group: Nathan Do, Kristy Chen, Wesley Trieu


-->


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
$restaurant_name = $cuisine = $phone_number = $state = $owner_id = $address = $city = "";
$restaurant_name_err = $cuisine_err = $phone_number_err = $state_err = $owner_id_err = $address_err = $city_err = "";

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

    // Validate phone_number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter the phone number.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Validate state (foreign key from RESTAURANT_LOCATION)
    if (empty($_POST["state"])) {
        $state_err = "Please select a state.";
    } else {
        $state = $_POST["state"];
    }

    // Validate address and city for restaurant location
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter the address.";
    } else {
        $address = trim($_POST["address"]);
    }

    if (empty(trim($_POST["city"]))) {
        $city_err = "Please enter the city.";
    } else {
        $city = trim($_POST["city"]);
    }

    // Validate owner_id
    if (empty($_POST["owner_id"])) {
        $owner_id = NULL;
    } else {
        $owner_id = trim($_POST["owner_id"]);
    }

    // Check for errors before inserting into the database
    if (empty($restaurant_name_err) && empty($cuisine_err) && empty($phone_number_err) && empty($state_err) && empty($address_err) && empty($city_err)) {
        // Begin transaction to ensure both tables are updated together
        mysqli_begin_transaction($link);

        try {
            // Step 1: Insert the location into the RESTAURANT_LOCATION table first
            $sql_location = "INSERT INTO RESTAURANT_LOCATION (address, state, city) VALUES (?, ?, ?)";
            if ($stmt_location = mysqli_prepare($link, $sql_location)) {
                mysqli_stmt_bind_param($stmt_location, "sss", $address, $state, $city);
        
                if (!mysqli_stmt_execute($stmt_location)) {
                    throw new Exception("Error inserting into RESTAURANT_LOCATION table: " . mysqli_error($link));
                }
        
                // Get the inserted location's location_id
                $location_id = mysqli_insert_id($link);
                mysqli_stmt_close($stmt_location);
            } else {
                throw new Exception("Error preparing the location insert statement.");
            }
        
            // Step 2: Insert the restaurant into the RESTAURANTS table
            $sql_restaurant = "INSERT INTO RESTAURANTS (restaurant_name, cuisine, phone_number, location_id, owner_id) VALUES (?, ?, ?, ?, ?)";
            if ($stmt_restaurant = mysqli_prepare($link, $sql_restaurant)) {
                mysqli_stmt_bind_param($stmt_restaurant, "ssssi", $restaurant_name, $cuisine, $phone_number, $location_id, $owner_id);
        
                if (!mysqli_stmt_execute($stmt_restaurant)) {
                    throw new Exception("Error inserting into RESTAURANTS table: " . mysqli_error($link));
                }
        
                mysqli_stmt_close($stmt_restaurant);
            } else {
                throw new Exception("Error preparing the restaurant insert statement.");
            }
        
            // Commit the transaction if all inserts succeed
            mysqli_commit($link);
        
            // Redirect to the main page
            header("Location: ../index.php");
            exit;
        
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            mysqli_rollback($link);
            echo "Error: " . $e->getMessage();
        }

        // Close the statement
        if (isset($stmt_restaurant)) {
            mysqli_stmt_close($stmt_restaurant);
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create New Restaurant</h2>
                    <p>Please fill this form to add a new restaurant.</p>
                    <form action="./create_restaurant.php" method="post">
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
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
                            <span class="invalid-feedback"><?php echo $phone_number_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <select name="state" class="form-control <?php echo (!empty($state_err)) ? 'is-invalid' : ''; ?>">
                                <option value="">Select State</option>
                                <?php 
                                $states = [
                                    "AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona", "AR" => "Arkansas", "CA" => "California",
                                    "CO" => "Colorado", "CT" => "Connecticut", "DE" => "Delaware", "FL" => "Florida", "GA" => "Georgia",
                                    "HI" => "Hawaii", "ID" => "Idaho", "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa",
                                    "KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland",
                                    "MA" => "Massachusetts", "MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri",
                                    "MT" => "Montana", "NE" => "Nebraska", "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey",
                                    "NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina", "ND" => "North Dakota", "OH" => "Ohio",
                                    "OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania", "RI" => "Rhode Island", "SC" => "South Carolina",
                                    "SD" => "South Dakota", "TN" => "Tennessee", "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont",
                                    "VA" => "Virginia", "WA" => "Washington", "WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming"
                                ];
                                foreach ($states as $abbr => $state) {
                                    echo "<option value=\"$abbr\" " . (($location == $abbr) ? "selected" : "") . ">$abbr - $state</option>";
                                }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $state_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $city; ?>">
                            <span class="invalid-feedback"><?php echo $city_err; ?></span>
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
