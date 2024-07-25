<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

$sql = "SELECT t.count, t.po_number, t.customer_name, t.quantity, 
               CASE WHEN d.assigned_to IS NOT NULL AND d.assigned_to <> '' THEN 'Assigned' ELSE 'On Hold' END AS assigned_to_status
        FROM pp_orders t
        LEFT JOIN dn_details d ON t.po_number = d.dn_number
        ORDER BY t.count";

$result = $conn->query($sql);

// Counter for serial number
$serialNumber = 1;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 50px auto;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;

        }
        th, td {
            padding: 15px;
            text-align: center; 
        }
        th {
            background-color:  #45a049;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:nth-child(odd) {
            background-color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        .status-assigned {
            color: green;
        }
        .status-on-hold {
            color: blue;
        }
    </style>
</head>
<body>
    <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
    <h1>Order List</h1>
    <table>
        <tr>
            <th>SI.NO</th>
            <th>PO</th>
            <th>Customer Name</th>
            <th>DN Status</th>
            <th>PO Quantity</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Check if assigned_to_status is 'Assigned' or 'On Hold'
                $dnStatus = ($row['assigned_to_status'] == 'Assigned') ? '<span class="status-assigned"><b>Assigned</b></span>' : '<span class="status-on-hold"><b>On Hold</b></span>';
                
                // Output row data with serial number
                echo "<tr>";
                echo "<td>" . $serialNumber . "</td>";
                echo "<td>" . htmlspecialchars($row["po_number"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["customer_name"]) . "</td>";
                echo "<td>" . $dnStatus . "</td>";
                echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                echo "</tr>";

                // Increment serial number
                $serialNumber++;
            }
        } else {
            echo "<tr><td colspan='5'>No orders found</td></tr>";
        }
        ?>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>
