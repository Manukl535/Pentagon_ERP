<?php
session_start();
include('../includes/connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mail_id'])) {
    $mailId = $_POST['mail_id'];
    $type = $_POST['type']; // 'trash' in this case

    // Assuming you have a trash table where emails are moved
    // Retrieve the email details from trash table
    $sqlSelect = "SELECT * FROM trash WHERE id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param('s', $mailId);
    $stmtSelect->execute();
    $resultSelect = $stmtSelect->get_result();

    if ($resultSelect->num_rows > 0) {
        $mailData = $resultSelect->fetch_assoc();

        // Insert into the mails table (or update as per your design)
        $sqlRestore = "INSERT INTO mails (from_email, to_email, subject, message, sent_at) VALUES (?, ?, ?, ?, ?)";
        $stmtRestore = $conn->prepare($sqlRestore);
        $stmtRestore->bind_param('sssss', $mailData['from_email'], $mailData['to_email'], $mailData['subject'], $mailData['message'], $mailData['deleted_at']);
        $stmtRestore->execute();

        // Delete from trash table
        $sqlDelete = "DELETE FROM trash WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param('s', $mailId);
        $stmtDelete->execute();

        // Return success message to JavaScript
        echo "success";
    } else {
        echo "error";
    }
}
?>
