<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pentagon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT count, po, customer_name, dn_status, po_quantity FROM pp_orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order List</title>
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
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
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
    </style>
</head>
<body>
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
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["count"]. "</td><td>" . $row["po"]. "</td><td>" . $row["customer_name"]. "</td><td>" . $row["dn_status"]. "</td><td>" . $row["po_quantity"]. "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No orders found</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
<!--give one more column with attribute details in header and open button in each button ,open button should be only enabled when dn status is generated ,on click of open button a modal box should be open and it should show the order corresponding to that customer, that modal should display  customer name,customer address, article number ,quantity,description and color it may have many article number ,quantity,description and colors -->