<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}


// Fetching form data
$vendor_id = $_POST['vendor_id'];
$vendor_name = $_POST['vendor_name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$gstin = $_POST['gstin'];

// Updating data in database
$query = "UPDATE vendors SET name = '$vendor_name', address = '$address', phone = '$phone', email = '$email', gst = '$gstin' WHERE vendor_id = '$vendor_id'";

if ($conn->query($query) === TRUE) {
    echo "Vendor updated successfully";
    header('Location: vendors.php');
} else {
    echo "Error updating vendor: " . $conn->error;
}

$conn->close();
?>
