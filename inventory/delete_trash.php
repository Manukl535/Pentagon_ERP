<?php
session_start();
include('../includes/connection.php');

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $mail_id = $_POST['mail_id'];
        $type = $_POST['type']; // Should be 'trash'

        // Validate mail_id (assuming it's numeric)
        if (!is_numeric($mail_id)) {
            echo "error";
            exit();
        }

        // Validate type (should be 'trash')
        if ($type !== 'trash') {
            echo "error";
            exit();
        }

        // SQL to delete from trash
        $sqlDelete = "DELETE FROM trash WHERE id = ? AND to_email = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param('ss', $mail_id, $email);

        if ($stmtDelete->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error"; // Handle unauthorized access
    }
} else {
    echo "error"; // Handle if not a POST request
}
?>
