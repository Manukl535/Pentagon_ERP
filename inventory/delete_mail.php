<?php
session_start();
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mail_id']) && isset($_POST['type'])) {
    $mailId = $_POST['mail_id'];
    $type = $_POST['type'];

    $tableName = ($type === 'inbox') ? 'mails' : 'sent_mails'; 

    // Retrieve the mail details before deleting
    $sqlSelect = "SELECT * FROM $tableName WHERE id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param('i', $mailId);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();

    if ($resultSelect->num_rows > 0) {
        $mailData = $resultSelect->fetch_assoc();

        // Insert into trash table
        $sqlInsert = "INSERT INTO trash (from_email, to_email, subject, message) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param('ssss', $mailData['from_email'], $mailData['to_email'], $mailData['subject'], $mailData['message']);
        $stmtInsert->execute();

        // Delete from original table
        $sqlDelete = "DELETE FROM $tableName WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param('i', $mailId);
        if ($stmtDelete->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        echo "error";
    }

    $stmtSelect->close();
    $stmtInsert->close();
    $stmtDelete->close();
} else {
    echo "error";
}

$conn->close();
?>
