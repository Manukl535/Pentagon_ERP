<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mail_id']) && isset($_POST['type'])) {
    $mailId = $_POST['mail_id'];
    $type = $_POST['type'];

    // Determine the target table and columns based on original deletion
    $tableName = 'mails'; // Assuming 'mails' table is used for both sent and received emails

    if ($type === 'trash') {
        // Retrieve email details from trash table
        $sqlSelect = "SELECT * FROM trash WHERE id = ?";
        $stmtSelect = $conn->prepare($sqlSelect);
        $stmtSelect->bind_param('i', $mailId);
        $stmtSelect->execute();
        $resultSelect = $stmtSelect->get_result();

        if ($resultSelect->num_rows > 0) {
            $mailData = $resultSelect->fetch_assoc();

            // Determine if the email was originally sent or received
            if (!empty($mailData['from_email'])) {
                // Restore to 'sent'
                $sqlRestore = "INSERT INTO $tableName (from_email, to_email, cc_email, subject, message, sent_at) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtRestore = $conn->prepare($sqlRestore);
                $stmtRestore->bind_param('ssssss', $mailData['from_email'], $mailData['to_email'], $mailData['cc_email'], $mailData['subject'], $mailData['message'], $mailData['sent_at']);
            } elseif (!empty($mailData['to_email'])) {
                // Restore to 'inbox'
                $sqlRestore = "INSERT INTO $tableName (from_email, to_email, cc_email, subject, message, sent_at) VALUES (?, ?, ?, ?, ?, ?)";
                $stmtRestore = $conn->prepare($sqlRestore);
                $stmtRestore->bind_param('ssssss', $mailData['to_email'], $mailData['from_email'], $mailData['cc_email'], $mailData['subject'], $mailData['message'], $mailData['sent_at']);
            }

            if ($stmtRestore->execute()) {
                // Delete from trash table
                $sqlDelete = "DELETE FROM trash WHERE id = ?";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->bind_param('i', $mailId);
                if ($stmtDelete->execute()) {
                    echo "success";
                } else {
                    echo "Error deleting from trash table: " . $stmtDelete->error;
                }
                $stmtDelete->close();
            } else {
                echo "Error restoring email: " . $stmtRestore->error;
            }
            $stmtRestore->close();
        } else {
            echo "Mail not found in trash table";
        }

        $stmtSelect->close();
    } else {
        echo "Invalid type or missing parameters";
    }
} else {
    echo "Parameters not set or incorrect request method";
}

$conn->close();
?>
