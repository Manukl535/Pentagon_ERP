<!-- approve_order.php -->

<?php
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['po'])) {
    $po = $_POST['po'];
    // Perform update query to mark order as approved in your database
    $sql = "UPDATE orders SET status = 'Approved' WHERE po = '$po'";
    if ($conn->query($sql) === TRUE) {
        // Success message (optional)
        echo "Order approved successfully";
    } else {
        // Error message (optional)
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
