<?php
// Include the database connection
include('../includes/connection.php');

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
    $query = "SELECT location FROM inv_location WHERE article_no = ? AND color = ? AND article_size = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $article_no, $color, $size);
    $stmt->execute();
    $stmt->bind_result($inv_location);
    $stmt->fetch();
    $stmt->close();

    // Insert into pp_orders table
    $insert_query = "INSERT INTO pp_orders (po_number, customer_name, address, phone, email, gstin, dod, article, color, size, quantity, inv_loc, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssssssssis", $po_number, $customer_name, $address, $phone, $email, $gstin, $date_of_delivery, $article_no, $color, $size, $quantity, $inv_location);
    
    if ($stmt->execute()) {
        // Insert successful
        $stmt->close();
        $conn->close();
        // Redirect back to transfer_order.php with success message
        header("Location: transfer_order.php?success=1");
        exit();
    } else {
        // Insert failed
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
