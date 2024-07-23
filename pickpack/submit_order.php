<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $customer_name = $_POST['customer_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gstin = $_POST['gst'];
    $date_of_delivery = $_POST['date_of_delivery'];
    $article_no = $_POST['article_no'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    
    // Generate a unique PO number dynamically (example: based on current timestamp)
    $po_number = 'PO-' . date('YmdHis');

    // Fetch location from inv_location based on article_no, color, size
    $query_location = "SELECT location FROM inv_location WHERE article_no = ? AND color = ? AND article_size = ?";
    $stmt_location = $conn->prepare($query_location);
    $stmt_location->bind_param("sss", $article_no, $color, $size);
    $stmt_location->execute();
    $stmt_location->bind_result($inv_location);
    $stmt_location->fetch();
    $stmt_location->close();

    // Insert into pp_orders table
    $insert_query_orders = "INSERT INTO pp_orders (po_number, customer_name, address, phone, email, gstin, dod, article, color, size, quantity, inv_loc, created_at) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt_orders = $conn->prepare($insert_query_orders);
    $stmt_orders->bind_param("ssssssssssis", $po_number, $customer_name, $address, $phone, $email, $gstin, $date_of_delivery, $article_no, $color, $size, $quantity, $inv_location);
    
    if ($stmt_orders->execute()) {
        // Insert into pp_orders successful
        $stmt_orders->close();

        // Insert into dn_details table with dn_quantity
        $insert_query_details = "INSERT INTO dn_details (dn_number, article, color, size, quantity, inv_loc, dn_quantity) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_details = $conn->prepare($insert_query_details);
        $stmt_details->bind_param("ssssisi", $po_number, $article_no, $color, $size, $quantity, $inv_location, $quantity); // Add $quantity as dn_quantity
        
        if ($stmt_details->execute()) {
            // Insert into dn_details successful
            $stmt_details->close();
            
            // Close the database connection
            $conn->close();
            
            // Redirect back to transfer_order.php with success message
            header("Location: transfer_order.php?success=1");
            exit();
        } else {
            // Insert into dn_details failed
            echo "Error inserting into dn_details: " . $stmt_details->error;
        }
    } else {
        // Insert into pp_orders failed
        echo "Error inserting into pp_orders: " . $stmt_orders->error;
    }
}

// Close the database connection
$conn->close();
// Redirect back to transfer_order.php with error message
?>
