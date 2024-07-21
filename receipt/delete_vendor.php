<?php
session_start();
include('../includes/connection.php');

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
