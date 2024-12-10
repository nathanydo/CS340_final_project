<?php
// Include config file
require_once "../../config/config.php";

// Check if the member_id is provided in the URL
if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];

    // Fetch the member details
    $sql = "SELECT * FROM MEMBERS WHERE member_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $member_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $member = mysqli_fetch_assoc($result);
            } else {
                echo "Member not found!";
                exit;
            }
        } else {
            echo "Error retrieving member details.";
            exit;
        }
        mysqli_stmt_close($stmt);
    }
} else {
    echo "No member selected!";
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_name = $_POST['member_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Update the member details
    $sql_update_member = "UPDATE MEMBERS SET member_name = ?, email = ?, phone_number = ? WHERE member_id = ?";
    if ($stmt = mysqli_prepare($link, $sql_update_member)) {
        mysqli_stmt_bind_param($stmt, "sssi", $member_name, $email, $phone_number, $member_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<p>Member updated successfully!</p>";
            header("Location: ../index.php"); // Redirect to the member list page or another relevant page
            exit;
        } else {
            echo "Error updating member.";
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
    <title>Update Member</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/index.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Update Member</h2>
        <form action="../update/update_member.php?member_id=<?php echo $member_id; ?>" method="POST">
            <div class="form-group">
                <label for="member_name">Member Name:</label>
                <input type="text" class="form-control" id="member_name" name="member_name" value="<?php echo htmlspecialchars($member['member_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($member['phone_number']); ?>" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="../read/read_members.php" class="btn btn-secondary ml-2">Cancel</a>
        </form>
    </div>
</body>
</html>
