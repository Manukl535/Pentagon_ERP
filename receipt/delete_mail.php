<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if the request method is POST and required parameters are set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mail_id']) && isset($_POST['type'])) {
    $mailId = $_POST['mail_id'];
    $type = $_POST['type'];

    // Determine the column and value to check based on type
    if ($type === 'sent') {
        $column = 'from_email';
        $tableName = 'mails';
    } elseif ($type === 'inbox') {
        $column = 'to_email';
        $tableName = 'mails';
    } else {
        echo "error"; // Invalid type
        exit;
    }

    // First, select mail details from the appropriate table
    $sqlSelect = "SELECT * FROM $tableName WHERE id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param('i', $mailId);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();

    // Check if mail exists in the selected table
    if ($resultSelect->num_rows > 0) {
        $mailData = $resultSelect->fetch_assoc();

        // Insert into trash table
        $sqlInsert = "INSERT INTO trash (from_email, to_email, subject, message, sent_at, deleted_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmtInsert = $conn->prepare($sqlInsert);
        
        if ($type === 'sent') {
            // For 'sent' type, reverse 'from_email' and 'to_email' for trash
            $stmtInsert->bind_param('sssss', $mailData['from_email'], $mailData['to_email'], $mailData['subject'], $mailData['message'], $mailData['sent_at']);
        } else {
            // For 'inbox' type, keep as is
            $stmtInsert->bind_param('sssss', $mailData['from_email'], $mailData['to_email'], $mailData['subject'], $mailData['message'], $mailData['sent_at']);
        }

        $stmtInsert->execute();

        // Check if insertion into trash was successful
        if ($stmtInsert->affected_rows > 0) {
            // Delete from original table
            $sqlDelete = "DELETE FROM $tableName WHERE id = ?";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bind_param('i', $mailId);
            if ($stmtDelete->execute()) {
                echo "success";
            } else {
                echo "error";
            }
            $stmtDelete->close();
        } else {
            echo "error";
        }
        $stmtInsert->close();
    } else {
        echo "error"; // Mail not found in the selected table
    }

    $stmtSelect->close();
} else {
    echo "error"; // Parameters not set or incorrect request method
}

$conn->close();
?>
