<?php
// fetch_vendors.php

// Include the database connection
include('../includes/connection.php');

// Get category from POST request
$category = $_POST['category'];

// Query to fetch vendors and their details for the selected category
$query = "SELECT name, address, phone, email, gst FROM vendors WHERE category = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $category);
$stmt->execute();
$result = $stmt->get_result();

// Fetch vendors into an array
$vendors = [];
while ($row = $result->fetch_assoc()) {
    $vendors[] = $row;
}

// Close the database connection
$stmt->close();
$conn->close();

// Return vendors as JSON
echo json_encode($vendors);
?>
