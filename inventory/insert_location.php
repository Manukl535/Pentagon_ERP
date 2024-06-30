<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user input for security
    $location = $conn->real_escape_string($_POST['location']);
    $capacity = $conn->real_escape_string($_POST['capacity']);

    try {
        // Insert query
        $insert_query = "INSERT INTO inv_location (location, capacity) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("si", $location, $capacity);
    
        if ($insert_stmt->execute()) {
            // Success message with alert
            echo '<script>alert("Location added successfully"); window.location.replace("index.php");</script>';
        } else {
            // Error message (this should ideally not execute if execution was successful)
            echo '<div class="error">Error: Unable to add location. Please try again later.</div>';
        }
    
        $insert_stmt->close();
    } catch (mysqli_sql_exception $e) {
        // Handle specific MySQL error code for duplicate entry
        if ($e->getCode() == 1062) {
            echo '<script>alert("Error: Location name not available. Please try again."); window.location.replace("manage_loc.php");</script>';
        } else {
            // Handle other exceptions or display a generic error message
            echo '<div class="error">Error: ' . $e->getMessage() . '</div>';
        }
    }
    
}

// Close connection
$conn->close();
?>
