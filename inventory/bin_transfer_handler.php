<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}


// Check if all necessary parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['location'], $_POST['article_no'], $_POST['quantity'], $_POST['description'], $_POST['color'], $_POST['article_size'], $_POST['category'], $_POST['destination_bin'])) {
    // Retrieve form data
    $location = $_POST['location'];
    $article_number = $_POST['article_no'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $color = $_POST['color'];
    $article_size = $_POST['article_size']; // Correctly fetch article_size
    $category = $_POST['category'];
    $destination_bin = $_POST['destination_bin'];

    // Retrieve available quantity and article_size from source location
    $query_source = "SELECT available_quantity, article_size FROM inv_location WHERE location = ?";
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
    if ($available_quantity >= $quantity && $quantity <= $capacity) {
        // Perform the transfer
        $query_transfer = "UPDATE inv_location SET article_no = ?, article_description = ?, color = ?, available_quantity = ?, article_size = ?, category = ? WHERE location = ?";
        $stmt_transfer = $conn->prepare($query_transfer);
        $stmt_transfer->bind_param("sssisss", $article_number, $description, $color, $quantity, $article_size, $category, $destination_bin);
        $stmt_transfer->execute();
        $stmt_transfer->close();

        // Update source location (reduce available quantity and reset other fields)
        $new_quantity = $available_quantity - $quantity;
        $query_update_source = "UPDATE inv_location SET available_quantity = ?, article_no = NULL, article_description = NULL, color = NULL, article_size = NULL, category = NULL WHERE location = ?";
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
    echo '<script>alert("Error: Missing parameters for transfer.");window.location.replace("bin_transfer.php");</script>';
}

// Close database connection
$conn->close();
?>
