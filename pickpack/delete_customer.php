<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if customer_id parameter is passed in POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['customer_id'])) {
    // Sanitize and retrieve customer_id
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);

    // Prepare DELETE statement
    $delete_sql = "DELETE FROM pp_customer WHERE customer_id = '$customer_id'";

    // Execute DELETE statement
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION['delete_success'] = "Customer deleted successfully."; // Set session variable
    } else {
        $_SESSION['delete_success'] = "Error deleting customer: " . $conn->error; // Set session variable on error
    }
} else {
    $_SESSION['delete_success'] = "Invalid request to delete customer."; // Set session variable for invalid request
}

// Close database connection
$conn->close();

// Redirect back to customer list page
header("Location: customer.php");
exit();
?>
