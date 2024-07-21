<?php
session_start();
include('../includes/connection.php');

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
