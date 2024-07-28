<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}


$sql = "SELECT orders.*, 
               CASE WHEN approved_po.po_number IS NOT NULL THEN 'Received' ELSE 'Not Received' END AS status_text,
               approved_po.approved_by AS received_by
        FROM orders
        LEFT JOIN approved_po ON orders.po = approved_po.po_number";

$result = $conn->query($sql);

// Counter for serial number
$serialNumber = 1;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Goods Ordered</title>
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
    <h1>Goods Ordered</h1>
    <table>
        <tr>
            <th>SI.NO</th>
            <th>PO Number</th>
            <th>Customer Name</th>
            <th>Ordered On</th>
            <th>Article</th>
            <th>Color</th>
            <th>Size</th>
            <th>PO Qty</th>
            <th>PO Status</th>
            <th>Received By</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Output row data with serial number
                echo "<tr>";
                echo "<td>" . $serialNumber . "</td>";
                echo "<td>" . htmlspecialchars($row["po"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["vendor"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["article_no"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["color"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["size"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                
                // Status column with conditional style
                if ($row["status_text"] == 'Received') {
                    echo "<td class='status-assigned'><strong>" . htmlspecialchars($row["status_text"]) . "</strong></td>";
                } else {
                    echo "<td class='status-on-hold'><strong>" . htmlspecialchars($row["status_text"]) . "</strong></td>";
                }
                
                
                echo "<td>" . htmlspecialchars(ucfirst($row["received_by"])) . "</td>";($row["received_by"]) . "</td>";
                echo "</tr>";

                // Increment serial number
                $serialNumber++;
            }
        } else {
            echo "<tr><td colspan='9'>No orders found</td></tr>";
        }
        ?>
    </table>

    <?php
    $conn->close();
    ?>
</body>
</html>