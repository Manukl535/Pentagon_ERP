<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $dept = mysqli_real_escape_string($conn, $_POST["dept"]);
    $issue = mysqli_real_escape_string($conn, $_POST["issue"]);

    // SQL query to insert values into the database table
    $sql = "INSERT INTO safety_reports (name, phone, dept, issue) 
            VALUES ('$name', '$phone', '$dept', '$issue')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Success message
        echo "<script>alert('Report submitted successfully!')</script>";
        header("Location: dashboard.php"); // Redirect to success page
        exit();
    } else {
        // Error message
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If the form was not submitted via POST method, handle accordingly
    echo "Form submission method not allowed.";
}
?>
