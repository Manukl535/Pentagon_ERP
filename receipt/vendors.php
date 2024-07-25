<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if there's a delete message
$delete_message = isset($_SESSION['delete_message']) ? $_SESSION['delete_message'] : '';
unset($_SESSION['delete_message']); // Clear session variable after displaying

// Fetch vendors data
$query = "SELECT * FROM vendors";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Vendors List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 800px; /* Increased width for better visibility */
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-row .form-group {
            flex-basis: calc(50% - 10px); /* Adjust width of each form group */
        }
        .form-row .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-row .form-group input, .form-row .form-group select {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff; /* Add background color for the table */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #4caf50;
            color: #fff;
            width: 10%; /* Set width for Vendor ID column */
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .edit-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .delete-button {
            background-color: red;
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .edit-button:hover {
            background-color: #45a049;
        }
        .delete-button:hover {
            background-color: darkred;
        }
        .button-container {
            text-align: center;
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
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
    <script>
        // Function to display modal and populate fields
        function openEditModal(vendorId, vendorName, address, phone, email, gstin) {
            var modal = document.getElementById("editModal");
            document.getElementById("edit_vendor_id").value = vendorId;
            document.getElementById("edit_vendor_name").value = vendorName;
            document.getElementById("edit_address").value = address;
            document.getElementById("edit_phone").value = phone;
            document.getElementById("edit_email").value = email;
            document.getElementById("edit_gstin").value = gstin;
            modal.style.display = "block";
        }

        // Function to close modal
        function closeEditModal() {
            var modal = document.getElementById("editModal");
            modal.style.display = "none";
        }
    </script>
</head>
<body>

<div class="container">
<a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
    <form id="vendorForm" method="POST" action="add_vendor.php">
        <div class="form-row">
            <div class="form-group">
                <label for="vendor_id">Vendor ID</label>
                <input type="text" id="vendor_id" name="vendor_id" placeholder="Vendor ID" required>
            </div>
            <div class="form-group">
                <label for="vendor_name">Vendor Name</label>
                <input type="text" id="vendor_name" name="vendor_name" placeholder="Vendor Name" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" placeholder="Address" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="Phone" pattern="[0-9]{10}" title="Please enter 10 digits" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" pattern="^[a-zA-Z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Please enter a valid email address" required>
            </div>
            <div class="form-group">
                <label for="gstin">GSTIN</label>
                <input type="text" id="gstin" name="gstin" placeholder="GSTIN" required>
            </div>
        </div>
        <input type="submit" id="submitButton" value="Add Vendor">
    </form>
</div>

<?php
if ($result->num_rows > 0) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Vendor ID</th>';
    echo '<th>Vendor Name</th>';
    echo '<th style="width:20%">Address</th>';
    echo '<th>Phone</th>';
    echo '<th>Email</th>';
    echo '<th>GSTIN</th>';
    echo '<th style="width:15%">Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row["vendor_id"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["name"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["address"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["phone"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["email"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["gst"]) . '</td>';
        echo '<td class="button-container">';
        echo '<button class="edit-button" onclick="openEditModal(\'' . htmlspecialchars($row["vendor_id"]) . '\', \'' . htmlspecialchars($row["name"]) . '\', \'' . htmlspecialchars($row["address"]) . '\', \'' . htmlspecialchars($row["phone"]) . '\', \'' . htmlspecialchars($row["email"]) . '\', \'' . htmlspecialchars($row["gst"]) . '\')">Edit</button>';
        echo '<form action="delete_vendor.php" method="POST" style="display: inline; margin-left: 5px;">';
        echo '<input type="hidden" name="vendor_id" value="' . htmlspecialchars($row["vendor_id"]) . '">';
        echo '<button type="submit" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this vendor?\')">Delete</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo "0 results";
}
?>

<!-- The Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Vendor</h2>
        <form action="update_vendor.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_vendor_id">Vendor ID</label>
                    <input type="text" id="edit_vendor_id" name="vendor_id" readonly>
                </div>
                <div class="form-group">
                    <label for="edit_vendor_name">Vendor Name</label>
                    <input type="text" id="edit_vendor_name" name="vendor_name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_address">Address</label>
                    <input type="text" id="edit_address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="edit_phone">Phone</label>
                    <input type="tel" id="edit_phone" name="phone" pattern="[0-9]{10}" title="Please enter 10 digits" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" pattern="^[a-zA-Z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Please enter a valid email address" required>
                </div>
                <div class="form-group">
                    <label for="edit_gstin">GSTIN</label>
                    <input type="text" id="edit_gstin" name="gstin" required>
                </div>
            </div>
            <input type="submit" value="Update">
        </form>
    </div>
</div>

</body>
</html>
