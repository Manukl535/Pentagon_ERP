<?php
session_start();
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gstin = mysqli_real_escape_string($conn, $_POST['gstin']);

    // Update customer record
    $update_sql = "UPDATE pp_customer SET 
                   customer_name = '$customer_name', 
                   address = '$address', 
                   phone = '$phone', 
                   email = '$email', 
                   gstin = '$gstin' 
                   WHERE customer_id = '$customer_id'";

    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['update_success'] = "Customer details updated successfully.";
    } else {
        $_SESSION['update_success'] = "Error updating customer details: " . $conn->error;
    }
}

$conn->close();

header("Location: customer.php");
exit();
?>
