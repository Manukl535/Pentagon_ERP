<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if mail_id and type are set in POST
if(isset($_POST['mail_id']) && isset($_POST['type'])) {
    $mail_id = $_POST['mail_id'];
    $type = $_POST['type'];

    // Prepare SQL statement to delete email from trash table
    $sql = "DELETE FROM trash WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $mail_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    // Close prepared statement
    $stmt->close();
} else {
    echo "error"; // Return error if parameters are not set
}

// Close database connection
$conn->close();
?>
