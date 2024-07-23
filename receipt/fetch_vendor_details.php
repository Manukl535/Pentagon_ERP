<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Get vendor name from GET request
if (isset($_GET['vendor'])) {
    $vendor = $_GET['vendor'];

    // Query to fetch vendor details
    $query = "SELECT address, phone, email, gst FROM vendors WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $vendor);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch vendor details
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array()); // Empty response if no data found
    }

    // Close the database connection
    $stmt->close();
}

$conn->close();
?>
