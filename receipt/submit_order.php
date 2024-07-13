<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize input data
    $vendor = htmlspecialchars($_POST['vendor']);
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $gst = htmlspecialchars($_POST['gst']);
    $date_of_delivery = htmlspecialchars($_POST['date_of_delivery']);
    $article_no = htmlspecialchars($_POST['article_no']);
    $color = htmlspecialchars($_POST['color']);
    $size = htmlspecialchars($_POST['size']);
    $quantity = intval($_POST['quantity']); // Ensure quantity is an integer
    
    // Additional validation checks can be added here
    
    // Connect to database (example assuming MySQL/MariaDB)
    include('../includes/connection.php'); // Adjust this path as per your file structure

    // Prepare SQL statement to insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (vendor, address, phone, email, gst, date_of_delivery, article_no, color, size, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters and execute the statement
    $stmt->bind_param("sssssssssi", $vendor, $address, $phone, $email, $gst, $date_of_delivery, $article_no, $color, $size, $quantity);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Order successfully inserted
        echo "<script>alert('Order submitted successfully!'); window.location.href = 'order_goods.php'</script>";
    } else {
        // Error inserting order
        echo "Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();

} else {
    // Redirect to the order form if accessed directly without POST method
    header("Location: order_goods.php");
    exit();
}
?>
