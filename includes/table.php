<?php
// Include config file
require_once "../config/config.php";

// Attempt select query execution
$sql = "SELECT * FROM RESTAURANTS";
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        echo '<table class="table table-bordered table-striped">';
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID#</th>";
        echo "<th>Restuarant</th>";
        echo "<th>Cuisine</th>";
        echo "<th>Location</th>";
        echo "<th>Phone Number</th>";
        echo "<th>Actions</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['restaurant_id'] . "</td>";
            echo "<td>" . $row['restaurant_name'] . "</td>";
            echo "<td>" . $row['cuisine'] . "</td>";
            echo "<td>" . $row['location'] . "</td>";
            echo "<td>" . $row['phone_number'] . "</td>";
            echo "<td>";
            echo '<a href="read.php?id=' . $row['id'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
            echo '<a href="update.php?id=' . $row['id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
            echo '<a href="delete.php?id=' . $row['id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        // Free result set
        mysqli_free_result($result);
    } else {
        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
    }
} else {
    echo "Oops! Something went wrong. Please try again later.";
}

// Close connection
mysqli_close($link);
?>