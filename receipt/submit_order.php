<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize inputs (you should add more validation as needed)
    $vendor = $_POST['vendor'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $gst = $_POST['gst'] ?? '';
    $date_of_delivery = $_POST['date_of_delivery'] ?? '';
    $article_no = $_POST['article_no'] ?? '';
    $color = $_POST['color'] ?? '';
    $size = $_POST['size'] ?? '';
    $quantity = $_POST['quantity'] ?? 0; // assuming default to 0 if not set
    
    // Generate dynamic po number
    if (!empty($vendor)) {
        // Extract first letters of each word in vendor name
        $vendor_words = explode(' ', $vendor);
        $first_letters = '';
        foreach ($vendor_words as $word) {
            $first_letters .= strtoupper(substr($word, 0, 1));
        }
        
        // Get current date for po number (MMDD format)
        $current_date = date('md');

        // Check if PO number for today's date already exists
        $existing_poes = []; // Assume this array stores existing PO numbers for today
        $suffix = 'A'; // Default suffix starting point

        // Find the next available suffix
        while (in_array($first_letters . $current_date . $suffix, $existing_poes)) {
            $suffix++;
            if ($suffix > 'Z') {
                $suffix = 'A'; // Loop back to 'A' after 'Z'
            }
        }

        // Combine to form po number
        $po = $first_letters . $current_date . $suffix;
    } else {
        $po = ''; // handle if vendor name is empty
    }
    
    // Additional processing or validation can go here
    
    // Example of inserting into database
    // Include your database connection script
    include('../includes/connection.php');

    // Example insert query (modify as per your database schema)
    $insertQuery = "INSERT INTO orders (po, vendor, address, phone, email, gst, date_of_delivery, article_no, color, size, quantity)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($insertQuery);
    if ($stmt === false) {
        // Handle error, e.g., log it or display an error message
        die('Error in preparing statement: ' . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param('ssssssssssi', $po, $vendor, $address, $phone, $email, $gst, $date_of_delivery, $article_no, $color, $size, $quantity);

    // Execute statement
    if ($stmt->execute()) {
        // Success message
        echo "<script>alert('Order submitted successfully!'); window.location.replace('order_goods.php');</script>";
    } else {
        // Error message
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

} else {
    // Redirect or handle unauthorized access
    echo "Unauthorized access!";
}
?>
