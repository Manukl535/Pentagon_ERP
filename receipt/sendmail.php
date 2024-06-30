<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Initialize variables to store success or error messages
$message = '';
$error = false;

// Process form submission to send email
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to_email = $_POST['to_email'];
    $cc_email = isset($_POST['cc_email']) ? $_POST['cc_email'] : '';
    $from_email = $_POST['from_email'];
    $subject = $_POST['subject'];
    $message_content = $_POST['message'];

    // Insert the email into the database
    $sql = "INSERT INTO mails (to_email, cc_email, from_email, subject, message) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssss', $to_email, $cc_email, $from_email, $subject, $message_content);

    if ($stmt->execute()) {
        // Email successfully sent
        $message = "Email sent successfully!";
    } else {
        // Error occurred while sending email
        $error = true;
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();

// Redirect back to mail.php with alert message
if ($error) {
    echo "<script>alert('Error occurred: $message'); window.location.href = 'mail.php';</script>";
} else {
    echo "<script>alert('$message'); window.location.href = 'mail.php';</script>";
}
?>
