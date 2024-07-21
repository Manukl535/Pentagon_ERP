<?php
// fetch_customer_details.php

// Include the database connection
include('../includes/connection.php');

// Check if customer_name is set and not empty
if (isset($_GET['customer_name']) && !empty($_GET['customer_name'])) {
    // Get the selected customer name from GET parameter
    $customer_name = $_GET['customer_name'];

    // Query to fetch customer details
    $query = "SELECT customer_name, address, phone, email, gstin FROM pp_customer WHERE customer_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $customer_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if customer details exist
    if ($result->num_rows > 0) {
        // Fetch data
        $row = $result->fetch_assoc();
        // Return JSON response
        echo json_encode($row);
    } else {
        // No customer found with the given name
        echo json_encode(['error' => 'Customer not found']);
    }
} else {
    // Handle case where customer_name parameter is missing or empty
    echo json_encode(['error' => 'Missing customer_name parameter']);
}

// Close the database connection
$conn->close();
?>
