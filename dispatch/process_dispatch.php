<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $modalDnNumber = sanitize_input($_POST["modalPoDnNumber"]);
    $modalPoQuantity = sanitize_input($_POST["modalPoQuantity"]);
    $modalProcessedQty = sanitize_input($_POST["modalProcessedQty"]);
    $modalCustomer = sanitize_input($_POST["modalCustomer"]);
    $modalAddress = sanitize_input($_POST["modalAddress"]);
    $modalPhone = sanitize_input($_POST["modalPhone"]);
    $modalEmail = sanitize_input($_POST["modalEmail"]);
    $modalGstin = sanitize_input($_POST["modalGstin"]);
    $processedBy = sanitize_input($_POST["processedBy"]);

    // Example SQL query to insert data into dispatched_orders table
    $sql_insert_dispatch = "INSERT INTO dispatched_orders (dn_number, po_qty, processed_qty, customer, address, phone, email, gstin, processed_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_dispatch = $conn->prepare($sql_insert_dispatch);
    $stmt_insert_dispatch->bind_param("sssssssss", $modalDnNumber, $modalPoQuantity, $modalProcessedQty, $modalCustomer, $modalAddress, $modalPhone, $modalEmail, $modalGstin, $processedBy);

    if ($stmt_insert_dispatch->execute()) {
        echo "Data inserted successfully";
        // Additional logic after successful insertion
    } else {
        echo "Error inserting data: " . $conn->error;
    }

    $stmt_insert_dispatch->close();
}

$conn->close();
?>
