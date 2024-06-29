<?php
session_start();
include('../includes/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mail_id'])) {
    $mailId = $_POST['mail_id'];
    
    // Update the mail record to mark it as read
    $sqlUpdate = "UPDATE mails SET is_read = 1 WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param('i', $mailId);
    
    if ($stmtUpdate->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmtUpdate->close();
} else {
    echo "error";
}

$conn->close();
?>
