<?php
// bin_transfer_handler.php

// Include your database connection file
include('../includes/connection.php');

// Check if all necessary parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['location'], $_POST['article_no'], $_POST['quantity'], $_POST['description'], $_POST['color'], $_POST['size'], $_POST['category'], $_POST['destination_bin'])) {
    // Retrieve form data
    $location = $_POST['location'];
    $article_number = $_POST['article_no'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $category = $_POST['category'];
    $destination_bin = $_POST['destination_bin'];

    // Retrieve available quantity and size from source location
    $query_source = "SELECT available_quantity, size FROM inv_location WHERE location = ?";
    $stmt_source = $conn->prepare($query_source);
    $stmt_source->bind_param("s", $location);
    $stmt_source->execute();
    $stmt_source->bind_result($available_quantity, $source_size);
    $stmt_source->fetch();
    $stmt_source->close();

    // Retrieve capacity from destination bin
    $query_destination = "SELECT capacity FROM inv_location WHERE location = ?";
    $stmt_destination = $conn->prepare($query_destination);
    $stmt_destination->bind_param("s", $destination_bin);
    $stmt_destination->execute();
    $stmt_destination->bind_result($capacity);
    $stmt_destination->fetch();
    $stmt_destination->close();

    // Check if transfer conditions are met
    if ($available_quantity >= $quantity && $available_quantity >= $capacity) {
        // Perform the transfer
        $query_transfer = "UPDATE inv_location SET article_no = ?, article_description = ?, color = ?, available_quantity = ?, size = ?, category = ? WHERE location = ?";
        $stmt_transfer = $conn->prepare($query_transfer);
        $stmt_transfer->bind_param("sssiiss", $article_number, $description, $color, $quantity, $source_size, $category, $destination_bin); // Ensure $source_size is used here
        $stmt_transfer->execute();
        $stmt_transfer->close();

        // Update source location (reduce available quantity and reset other fields)
        $new_quantity = $available_quantity - $quantity;
        $query_update_source = "UPDATE inv_location SET available_quantity = ?, article_no = NULL, article_description = NULL, color = NULL, size = NULL, category = NULL WHERE location = ?";
        $stmt_update_source = $conn->prepare($query_update_source);
        $stmt_update_source->bind_param("is", $new_quantity, $location);
        $stmt_update_source->execute();
        $stmt_update_source->close();

        // Success alert
        echo '<script>alert("Transfer successful!");window.location.replace("bin_transfer.php");</script>';
    } else {
        // Failure alert
        echo '<script>alert("Transfer failed: Source location quantity does not meet or exceed destination bin capacity.");window.location.replace("bin_transfer.php");</script>';
    }
} else {
    // Error alert
    echo '<script>alert("Error: Missing parameters for transfer.");</script>';
}

// Close database connection
$conn->close();
?>
