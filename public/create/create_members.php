<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Include the database config
require_once "../../config/config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $member_id = $_POST['member_id'];
    $name = $_POST['username'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    // Insert the restaurant into the database
    $sql = "INSERT INTO MEMBERS (username, email, dob) 
            VALUES (?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $dob);
        
        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo "<p>Restaurant added successfully!</p>";
            header("Location: read_members.php"); // Redirect to the list of restaurants
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
    <title>Create Member</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Add a New Member</h2>
                    <p>Please fill this form to add a new member.</p>
                    <form action="create_members.php" method="POST">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth:</label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a href="../read/read_members.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>