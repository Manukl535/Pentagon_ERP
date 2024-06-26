<?php
session_start();
include('../includes/connection.php'); // Adjust the path to your connection file

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user input for security
    $delete_location = $conn->real_escape_string($_POST['delete_location']);

    // Check if location exists before deletion
    $check_query = "SELECT * FROM inv_location WHERE location = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $delete_location);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows == 0) {
        // Location does not exist, show alert
        echo '<script>alert("Location does not exist"); window.location.replace("index.php");</script>';
    } else {
        // Location exists, proceed with deletion
        $delete_query = "DELETE FROM inv_location WHERE location = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("s", $delete_location);

        if ($delete_stmt->execute()) {
            echo '<script>alert("Location deleted successfully"); window.location.replace("index.php");</script>';
        } else {
            echo '<script>alert("Error: ' . $delete_stmt->error . '"); window.location.replace("index.php");</script>';
        }
        
        $delete_stmt->close();
    }

    $check_stmt->close();
}

// Close connection
$conn->close();
?>
