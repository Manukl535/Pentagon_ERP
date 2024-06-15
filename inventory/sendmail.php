<?php
session_start();
include('../includes/connection.php');

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $to_email = $_POST['to_email'];
  $cc_email = $_POST['cc_email'];
  // Retrieve from_email from session variable
  $from_email = $_SESSION['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Insert data into the database
  $sql = "INSERT INTO mails (to_email, cc_email, from_email, subject, message)
          VALUES ('$to_email', '$cc_email', '$from_email', '$subject', '$message')";

  if ($conn->query($sql) === TRUE) {
    echo '<script>alert("Mail Sent Successfully");</script>';
    header("location: ./mail.php");
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    header("location: ./mail.php");
  }
}
?>
