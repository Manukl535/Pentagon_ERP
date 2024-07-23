<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve customer information
    $customerName = $_POST["customerName"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];

    // Generate a single DN number for the order
    $dnNumber = "DN-" . date("Y") . "-" . rand(100, 999);

    // Insert PO details into 'po_details' table
    $sqlInsertPO = "INSERT INTO po_details (po_number, customer_name, address, phone, email)
                    VALUES ('$dnNumber', '$customerName', '$address', '$phone', '$email')";
    if ($conn->query($sqlInsertPO) === TRUE) {
        $poId = $conn->insert_id; // Get the ID of the inserted PO record
        echo "PO details inserted successfully.<br>";
    } else {
        echo "Error: " . $sqlInsertPO . "<br>" . $conn->error;
    }

    // Insert each article into 'dn_details' table with the same DN number
    if (isset($_POST["articles"]) && is_array($_POST["articles"])) {
        foreach ($_POST["articles"] as $article) {
            $articleName = $article["article"];
            $articleSize = $article["size"];
            $articleQty = $article["qty"];

            // Insert DN details into 'dn_details' table
            $sqlInsertDN = "INSERT INTO dn_details (dn_number, po_id, article, size, qty)
                            VALUES ('$dnNumber', '$poId', '$articleName', '$articleSize', '$articleQty')";
            if ($conn->query($sqlInsertDN) === TRUE) {
                echo "DN details for '$articleName' inserted successfully.<br>";
            } else {
                echo "Error: " . $sqlInsertDN . "<br>" . $conn->error;
            }
        }
    }
}

// Close connection
$conn->close();
?>