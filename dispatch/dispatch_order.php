<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Initialize variables
$po_number = "";
$order_details = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['po_number'])) {
    $po_number = $_POST['po_number'];

    // Fetch order details for the selected DN number
    $sql = "SELECT dn_number, assigned_to, po_qty, approved_by, customer_name, address, phone, email, gstin FROM picked_po WHERE dn_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $po_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order_details = $result->fetch_assoc();
    }
    $stmt->close();
}

// Handle dispatch form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dispatch'])) {
    // Assuming 'dispatched_orders' table structure and insert query
    $sql = "INSERT INTO dispatched_orders (dn_number, assigned_to, po_qty, approved_by, customer_name, address, phone, email, gstin, dispatched_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $_POST['dn_number'], $_POST['assigned_to'], $_POST['po_qty'], $_POST['approved_by'], $_POST['customer_name'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['gstin'], $_POST['dispatched_by']);

    // Set parameters and execute
    $stmt->execute();
    $stmt->close();

// Notify user and redirect using JavaScript
echo "<script>alert('Dispatched successfully!'); window.location.href = '".$_SERVER['PHP_SELF']."';</script>";
exit();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goods Receipt Note (GRN)</title>
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
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            margin-bottom: 5px;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
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
            background-color: #f2f2f2;
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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 10% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 500px; /* Max width */
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            position: relative;
        }

        .close {
            color: #aaa;
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* Adjust form inside modal */
        #approveForm {
            margin-top: 20px;
        }

        #approveForm label {
            display: block;
            margin-bottom: 5px;
        }

        #approveForm input[type=text], #approveForm button {
            padding: 8px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box; /* Ensure padding and border do not increase element width */
            margin-bottom: 10px;
        }

        #approveForm button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
        }

        #approveForm button:hover {
            background-color: #45a049;
        }

        /* Additional styles for modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 10% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 500px; /* Max width */
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            position: relative;
        }

        .close {
            color: #aaa;
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        #dispatchForm label {
            display: block;
            margin-bottom: 5px;
        }

        #dispatchForm input[type=text], #dispatchForm button {
            padding: 8px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box; /* Ensure padding and border do not increase element width */
            margin-bottom: 10px;
        }

        #dispatchForm button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
        }

        #dispatchForm button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Dispatch Order</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="po_number">Select DN Number:</label>
    <select name="po_number" id="po_number">
        <option value="" selected disabled>Select DN Number</option>
        <?php
        // Fetch dn_number from picked_po table, excluding dispatched orders
$sql = "SELECT dn_number FROM picked_po WHERE dn_number NOT IN (SELECT dn_number FROM dispatched_orders)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dn_number = htmlspecialchars($row['dn_number']);
        echo "<option value='$dn_number' " . ($po_number == $dn_number ? 'selected' : '') . ">$dn_number</option>";
    }
} else {
    echo "<option value=''>No DN numbers found</option>";
}

        ?>
    </select>
    <button type="submit">Get Details</button>
</form>

<hr>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($order_details)): ?>
    <h2>Order Details for DN Number: <?php echo htmlspecialchars($po_number); ?></h2>
    <table>
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
            <th>Action</th>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($order_details['dn_number']); ?></td>
            <td><?php echo htmlspecialchars($order_details['assigned_to']); ?></td>
            <td><?php echo htmlspecialchars($order_details['po_qty']); ?></td>
            <td><?php echo htmlspecialchars($order_details['approved_by']); ?></td>
            <td><?php echo htmlspecialchars($order_details['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($order_details['address']); ?></td>
            <td><?php echo htmlspecialchars($order_details['phone']); ?></td>
            <td><?php echo htmlspecialchars($order_details['email']); ?></td>
            <td><?php echo htmlspecialchars($order_details['gstin']); ?></td>
            <td><button class="approve-btn" onclick="openModal('<?php echo htmlspecialchars($order_details['dn_number']); ?>', '<?php echo htmlspecialchars($order_details['assigned_to']); ?>', '<?php echo htmlspecialchars($order_details['po_qty']); ?>', '<?php echo htmlspecialchars($order_details['approved_by']); ?>', '<?php echo htmlspecialchars($order_details['customer_name']); ?>', '<?php echo htmlspecialchars($order_details['address']); ?>', '<?php echo htmlspecialchars($order_details['phone']); ?>', '<?php echo htmlspecialchars($order_details['email']); ?>', '<?php echo htmlspecialchars($order_details['gstin']); ?>')">Dispatch</button></td>
        </tr>
    </table>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Dispatch Details for DN Number: <span id="modalDnNumber"></span></h3>
            <form id="dispatchForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" name="dn_number" id="modalDnNumberInput">
                <label for="assigned_to">Assigned To:</label>
                <input type="text" id="modalAssignedTo" name="assigned_to" readonly>
                <label for="po_qty">PO Quantity:</label>
                <input type="text" id="modalPoQty" name="po_qty" readonly>
                <label for="approved_by">Approved By:</label>
                <input type="text" id="modalApprovedBy" name="approved_by" readonly>
                <label for="customer_name">Customer Name:</label>
                <input type="text" id="modalCustomerName" name="customer_name" readonly>
                <label for="address">Address:</label>
                <input type="text" id="modalAddress" name="address" readonly>
                <label for="phone">Phone:</label>
                <input type="text" id="modalPhone" name="phone" readonly>
                <label for="email">Email:</label>
                <input type="text" id="modalEmail" name="email" readonly>
                <label for="gstin">GSTIN:</label>
                <input type="text" id="modalGstin" name="gstin" readonly>
                <label for="dispatched_by">Dispatched By:</label>
                <input type="text" id="dispatchedBy" name="dispatched_by">
                <button type="submit" name="dispatch">Dispatch</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(dnNumber, assignedTo, poQty, approvedBy, customerName, address, phone, email, gstin) {
            document.getElementById("modalDnNumber").innerText = dnNumber;
            document.getElementById("modalDnNumberInput").value = dnNumber;
            document.getElementById("modalAssignedTo").value = assignedTo;
            document.getElementById("modalPoQty").value = poQty;
            document.getElementById("modalApprovedBy").value = approvedBy;
            document.getElementById("modalCustomerName").value = customerName;
            document.getElementById("modalAddress").value = address;
            document.getElementById("modalPhone").value = phone;
            document.getElementById("modalEmail").value = email;
            document.getElementById("modalGstin").value = gstin;
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }
    </script>

<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <p>No orders found for DN Number: <?php echo htmlspecialchars($po_number); ?></p>
<?php endif; ?>

</body>
</html>
