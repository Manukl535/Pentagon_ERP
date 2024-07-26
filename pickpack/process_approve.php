<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to handle SQL errors
function handle_sql_error($conn) {
    echo "Error: " . $conn->error;
    exit(); // Stop further execution
}

// Process form submission from modal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approved_by'])) {
    // Sanitize inputs
    $dn_number = sanitize_input($_POST['dn_number']);
    $assigned_to = sanitize_input($_POST['assigned_to']);
    $dn_quantity = sanitize_input($_POST['dn_quantity']);
    $po_number = sanitize_input($_POST['po_number']);
    $customer_name = sanitize_input($_POST['customer_name']);
    $address = sanitize_input($_POST['address']);
    $phone = sanitize_input($_POST['phone']);
    $email = sanitize_input($_POST['email']);
    $gstin = sanitize_input($_POST['gstin']);
    $approved_by = sanitize_input($_POST['approved_by']);

    // Validate inputs (basic validation)
    if (empty($dn_number) || empty($assigned_to) || empty($dn_quantity) || empty($po_number) || empty($customer_name) || empty($address) || empty($phone) || empty($email) || empty($gstin) || empty($approved_by)) {
        echo "<script>alert('Error: Missing required parameters.'); window.location.href = 'assign.php';</script>";
        exit(); // Stop further execution
    }

    // Prepare SQL statement to insert into picked_po table
    $insert_sql = "INSERT INTO picked_po (dn_number, assigned_to, po_qty, approved_by, customer_name, address, phone, email, gstin) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_insert->bind_param("sssssssss", $dn_number, $assigned_to, $dn_quantity, $approved_by, $customer_name, $address, $phone, $email, $gstin);

    // Execute SQL statement to insert into picked_po table
    if ($stmt_insert->execute()) {
        // Update dn_details table to mark as approved
        $update_sql = "UPDATE dn_details SET approved = 1 WHERE dn_number = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("s", $dn_number);

        // Execute SQL statement to update dn_details table
        if ($stmt_update->execute()) {
            echo "<script>alert('Delivery Note approved and data inserted into picked_po successfully.'); window.location.href = 'assign.php';</script>";
            exit();
        } else {
            handle_sql_error($conn);
        }
    } else {
        handle_sql_error($conn);
    }
} else {
    // Handle invalid request
    echo "<script>alert('Invalid request.'); window.location.href = 'assign.php';</script>";
    exit();
}
?>
