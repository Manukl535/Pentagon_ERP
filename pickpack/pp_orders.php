<?php
// Database connection details
include('../Includes/connection.php');

// Fetch data from the database
$sql = "SELECT count, po, customer_name, dn_status, po_quantity FROM pp_orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-open {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 12px;
        }
        .btn-open:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function openModal(si_no) {
            fetch('todn.php?count=' + count)
                .then(response => response.json())
                .then(data => {
                    let modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = `<h2>Order Details for ${data.customer_name}</h2>
                                              <p>Customer Address: ${data.customer_address}</p>`;
                    data.items.forEach(item => {
                        modalContent.innerHTML += `<p>Article Number: ${item.article_number}</p>
                                                    <p>Quantity: ${item.quantity}</p>
                                                    <p>Description: ${item.description}</p>
                                                    <p>Color: ${item.color}</p>
                                                    <hr>`;
                    });
                    document.getElementById('myModal').style.display = "block";
                });
        }
        function closeModal() {
            document.getElementById('myModal').style.display = "none";
        }
    </script>
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
            <th>Details</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["count"]. "</td><td>" . $row["po"]. "</td><td>" . $row["customer_name"]. "</td><td>" . $row["dn_status"]. "</td><td>" . $row["po_quantity"]. "</td>";
                echo "<td><button class='btn-open' onclick=\"openModal(" . $row["count"] . ")\"> Open</button></td></tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No orders found</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalContent"></div>
        </div>
    </div>
</body>
</html>
