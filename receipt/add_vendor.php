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

// Inserting data into database
$query = "INSERT INTO vendors (vendor_id, name, address, phone, email, gst) VALUES ('$vendor_id', '$vendor_name', '$address', '$phone', '$email', '$gstin')";

if ($conn->query($query) === TRUE) {
    echo "New vendor added successfully";
    header('Location: vendors.php');
} else {
    echo "Error: " . $query . "<br>" . $conn->error;
}

$conn->close();
?>
