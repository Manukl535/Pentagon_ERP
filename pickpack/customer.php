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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST["customer_id"];
    $customer_name = $_POST["customer_name"];
    $company_name = $_POST["company_name"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $gstin = $_POST["gstin"];
    $row_index = $_POST["row_index"];

    if ($row_index) {
        $stmt = $conn->prepare("UPDATE pp_customer SET customer_id=?, customer_name=?, company_name=?, address=?, phone=?, email=?, gstin=? WHERE id=?");
        $stmt->bind_param("sssssssi", $customer_id, $customer_name, $company_name, $address, $phone, $email, $gstin, $row_index);
    } else {
        $stmt = $conn->prepare("INSERT INTO pp_customer (customer_id, customer_name, company_name, address, phone, email, gstin) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $customer_id, $customer_name, $company_name, $address, $phone, $email, $gstin);
    }

    $stmt->execute();
        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            $_SESSION['customer_added'] = true; // Set session variable
        }
    $stmt->close();
}

// Retrieve customer data
$result = $conn->query("SELECT * FROM pp_customer");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border: 2px solid #000;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container form input {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container form button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container form button:hover {
            background-color: #45a049;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid #000;
        }
        tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        tr:nth-child(even) {
            background-color: #e0e0e0;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .action-button {
            padding: 5px 10px;
            margin: 0 5px;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .edit-button {
            background-color: #008CBA;
        }
        .edit-button:hover {
            background-color: #007bb5;
        }
        .delete-button {
            background-color: #f44336;
        }
        .delete-button:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

<div class="form-container">
    <form id="customerForm" method="POST" action="customer.php">
        <input type="hidden" id="row_index" name="row_index">
        <input type="text" id="customer_id" name="customer_id" placeholder="Customer ID" required>
        <input type="text" id="customer_name" name="customer_name" placeholder="Customer Name" required>
        <input type="text" id="company_name" name="company_name" placeholder="Company Name" required>
        <input type="text" id="address" name="address" placeholder="Address" required>
        <input type="text" id="phone" name="phone" placeholder="Phone" required>
        <input type="email" id="email" name="email" placeholder="Email" required>
        <input type="text" id="gstin" name="gstin" placeholder="GSTIN" required>
        <button type="submit">Add Customer</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Company Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Email</th>
            <th>GSTIN</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="customerTableBody">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["customer_id"]; ?></td>
                    <td><?php echo $row["customer_name"]; ?></td>
                    <td><?php echo $row["company_name"]; ?></td>
                    <td><?php echo $row["address"]; ?></td>
                    <td><?php echo $row["phone"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo $row["gstin"]; ?></td>
                    <td>
                        <button class="action-button edit-button" onclick="">Edit</button>
                        <button class="action-button delete-button" onclick="">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No customers found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    function editRow(element, id) {
        const row = element.parentNode.parentNode;
        const cells = row.getElementsByTagName('td');

        // Fill form with current row values
        document.getElementById('row_index').value = id;
        document.getElementById('customer_id').value = cells[0].innerText;
        document.getElementById('customer_name').value = cells[1].innerText;
        document.getElementById('company_name').value = cells[2].innerText;
        document.getElementById('address').value = cells[3].innerText;
        document.getElementById('phone').value = cells[4].innerText;
        document.getElementById('email').value = cells[5].innerText;
        document.getElementById('gstin').value = cells[6].innerText;
    }


    function deleteRow(element, id) {
        if (confirm("Are you sure you want to delete this customer?")) {
            window.location.href = `delete_customer.php?id=${id}`;
        }
    }

            // Check if session variable is set and show alert
            <?php
        if (isset($_SESSION['customer_added']) && $_SESSION['customer_added']) {
            echo 'window.onload = function() { alert("Customer added successfully"); };';
            unset($_SESSION['customer_added']); // Unset session variable after displaying alert
        }
        ?>
</script>

</body>
</html>
