<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to_email = $_POST['to_email'];
    $cc_email = isset($_POST['cc_email']) ? $_POST['cc_email'] : '';
    $from_email = $_POST['from_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $reply_to = $_POST['reply_to'];

    // Insert reply mail into database or send it via mail function, depending on your application

    // Example: Insert reply into a database
    $sql = "INSERT INTO mails (to_email, cc_email, from_email, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $to_email, $cc_email, $from_email, $subject, $message);
    $stmt->execute();

    // Handle success or failure
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Reply sent successfully'); window.location.href = 'mail.php';</script>";
    } else {
        echo "error";
    }
}
?>
