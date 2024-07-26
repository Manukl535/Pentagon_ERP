<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Fetch dispatched orders from database
$sql = "SELECT * FROM dispatched_orders";
$result = $conn->query($sql);

// Initialize an array to store dispatched orders
$dispatch_history = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dispatch_history[] = $row;
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: ;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<h2>Dispatch History</h2>

<?php if (!empty($dispatch_history)): ?>
    <table>
        <thead>
            <tr>
                <th>DN Number</th>
                <th>Assigned To</th>
                <th>PO Quantity</th>
                <th>Approved By</th>
                <th>Customer Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>GSTIN</th>
                <th>Dispatched By</th>
                <th>Dispatched On</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dispatch_history as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['dn_number']); ?></td>
                    <td><?php echo htmlspecialchars($order['assigned_to']); ?></td>
                    <td><?php echo htmlspecialchars($order['po_qty']); ?></td>
                    <td><?php echo htmlspecialchars($order['approved_by']); ?></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                    <td><?php echo htmlspecialchars($order['gstin']); ?></td>
                    <td><?php echo htmlspecialchars($order['dispatched_by']); ?></td>
                    <td><?php echo htmlspecialchars($order['dispatch_date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No dispatch history found.</p>
<?php endif; ?>

</body>
</html>
