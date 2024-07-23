<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if data is sent via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from POST
    $po = $_POST['po'];
    $article_no = $_POST['article_no'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $quantity = $_POST['qty'];
    $approved_by = $_POST['approved_by'];

    // Start transaction (assuming you want to ensure both insert and update succeed or fail together)
    $conn->begin_transaction();

    try {
        // Insert into approved_po table
        $sql_insert = "INSERT INTO approved_po (po_number, article, color, size, quantity, approved_by) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssss", $po, $article_no, $color, $size, $quantity, $approved_by);
        $stmt_insert->execute();

        // Update orders table to set approved_by
        $sql_update = "UPDATE orders SET approved_by = ? WHERE po = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $approved_by, $po);
        $stmt_update->execute();

        // Commit transaction if all queries succeed
        $conn->commit();

        // Notify success
        echo "PO approved successfully.";
    } catch (Exception $e) {
        // Rollback transaction if any query fails
        $conn->rollback();

        // Output error message for debugging
        echo "Error: " . $e->getMessage();
    }

    // Close statements
    $stmt_insert->close();
    $stmt_update->close();

} else {
    // No POST data received
    echo "No data received.";
}
?>
