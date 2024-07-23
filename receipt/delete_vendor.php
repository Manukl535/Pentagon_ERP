<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}


// Fetching vendor_id from POST
$vendor_id = $_POST['vendor_id'];

// Deleting vendor from database
$query = "DELETE FROM vendors WHERE vendor_id = '$vendor_id'";

if ($conn->query($query) === TRUE) {
    $_SESSION['delete_message'] = "Vendor deleted successfully";
    header('Location: vendors.php');
    exit();
} else {
    $_SESSION['delete_message'] = "Error deleting vendor: " . $conn->error;
}

$conn->close();
header('Location: vendors.php');
exit();
?>
