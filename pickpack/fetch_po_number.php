<?php
include('../Includes/connection.php');

// Function to handle SQL errors
function handle_sql_error($conn) {
    echo "Error: " . $conn->error;
    exit(); // Stop further execution
}

// Validate and sanitize input
if (isset($_GET['dn_number'])) {
    $dn_number = $_GET['dn_number'];

    // Prepare SQL statement to fetch po_number and additional fields
    $sql = "SELECT po.po_number, po.customer_name, po.address, po.phone, po.email, po.gstin
            FROM pp_orders po
            INNER JOIN dn_details dn ON po.po_number = dn.dn_number
            WHERE dn.dn_number = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dn_number);

    // Execute SQL statement
    if ($stmt->execute()) {
        $stmt->bind_result($po_number, $customer_name, $address, $phone, $email, $gstin);
        if ($stmt->fetch()) {
            $data = array(
                'po_number' => $po_number,
                'customer_name' => $customer_name,
                'address' => $address,
                'phone' => $phone,
                'email' => $email,
                'gstin' => $gstin
            );
            echo json_encode($data); // Return JSON encoded data
        } else {
            echo "Not found"; // Handle if no matching record is found
        }
    } else {
        handle_sql_error($conn); // Handle SQL execution error
    }

    $stmt->close();
} else {
    echo "Invalid request"; // Handle if dn_number parameter is missing
}

$conn->close();
?>
