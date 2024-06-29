<?php
session_start();
include('../includes/connection.php'); 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user input for security
    $delete_location = $conn->real_escape_string($_POST['delete_location']);
    $remarks = $conn->real_escape_string($_POST['remarks']);

    // Check if location exists before deletion
    $check_query = "SELECT * FROM inv_location WHERE location = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $delete_location);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows == 0) {
        // Location does not exist, show alert
        echo '<script>alert("Location does not exist"); window.location.replace("index.php");</script>';
    } else {
        // Location exists, check additional conditions
        $location_data = $result->fetch_assoc();
        $article = $location_data['article'];
        $available_quantity = $location_data['available_quantity'];

        // Check conditions for deletion (example conditions)
        if ($article == 'some_value' && $available_quantity > 0) {
            // Proceed with deletion
            $delete_query = "DELETE FROM inv_location WHERE location = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("s", $delete_location);

            if ($delete_stmt->execute()) {
                // Log the deletion with remarks
                $log_query = "INSERT INTO deletion_logs (location, remarks) VALUES (?, ?)";
                $log_stmt = $conn->prepare($log_query);
                $log_stmt->bind_param("ss", $delete_location, $remarks);
                $log_stmt->execute();
                
                echo '<script>alert("Location deleted successfully"); window.location.replace("index.php");</script>';
            } else {
                // Error message
                echo '<div class="error">Error: ' . $delete_stmt->error . '</div>';
            }
            
            $delete_stmt->close();
        } else {
            // Conditions not met, show alert
            echo '<script>alert("Location cannot be deleted due to specific conditions"); window.location.replace("index.php");</script>';
        }
    }

    $check_stmt->close();
}

// Close connection
$conn->close();
?>
