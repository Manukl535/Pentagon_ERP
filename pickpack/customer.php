<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if form is submitted for adding new customer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $gstin = mysqli_real_escape_string($conn, $_POST['gstin']);

    // Check if customer ID already exists
    $check_sql = "SELECT * FROM pp_customer WHERE customer_id = '$customer_id'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        echo '<script>alert("Customer ID already exists. Please choose a different Customer ID.");</script>';
    } else {
        // Insert into database
        $insert_sql = "INSERT INTO pp_customer (customer_id, customer_name, address, phone, email, gstin) 
                       VALUES ('$customer_id', '$customer_name', '$address', '$phone', '$email', '$gstin')";
        if ($conn->query($insert_sql) === TRUE) {
            echo '<script>alert("New customer added successfully.");</script>';
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
}

// Fetch data from the pp_customer table
$sql = "SELECT * FROM pp_customer";
$result = $conn->query($sql);

// Check for delete success session message
$delete_message = "";
if (isset($_SESSION['delete_success'])) {
    $delete_message = $_SESSION['delete_success'];
    unset($_SESSION['delete_success']); // Unset the session variable
}

// Check for update success session message
$update_message = "";
if (isset($_SESSION['update_success'])) {
    $update_message = $_SESSION['update_success'];
    unset($_SESSION['update_success']); // Unset the session variable
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Customer List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
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
            text-align: left;
        }
        th {
            background-color: #4caf50;
            color: #fff;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .edit-button{
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px; /* Adjust padding as needed */
            border: none;
            cursor: pointer;
            border-radius: 4px; /* Rounded corners */
            transition: background-color 0.3s ease; /* Smooth transition on hover */
        } 
        .delete-button {
            background-color: red;
            color: white;
            padding: 8px 16px; /* Adjust padding as needed */
            border: none;
            cursor: pointer;
            border-radius: 4px; /* Rounded corners */
            transition: background-color 0.3s ease; /* Smooth transition on hover */
        }

        .edit-button:hover {
            background-color: #45a049; /* Darker shade on hover */
        }
        .delete-button:hover {
            background-color: red; /* Darker shade on hover */
        }

        /* Optional: Center buttons horizontally */
        .button-container {
            text-align: center;
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
            border-radius: 8px;
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
    </style>
    <script>
        // JavaScript to display delete success message if present
        <?php if (!empty($delete_message)): ?>
            alert("<?php echo $delete_message; ?>");
        <?php endif; ?>

        // JavaScript to display update success message if present
        <?php if (!empty($update_message)): ?>
            alert("<?php echo $update_message; ?>");
        <?php endif; ?>

        // Function to display modal and populate fields
        function openEditModal(customerId, customerName, address, phone, email, gstin) {
            // Get the modal
            var modal = document.getElementById("editModal");

            // Populate fields with customer data
            document.getElementById("edit_customer_id").value = customerId;
            document.getElementById("edit_customer_name").value = customerName;
            document.getElementById("edit_address").value = address;
            document.getElementById("edit_phone").value = phone;
            document.getElementById("edit_email").value = email;
            document.getElementById("edit_gstin").value = gstin;

            // Display the modal
            modal.style.display = "block";
        }

        // Function to close modal
        function closeEditModal() {
            // Get the modal
            var modal = document.getElementById("editModal");

            // Close the modal
            modal.style.display = "none";
        }
    </script>
</head>
<body>

<div class="container">
    <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
    <form id="customerForm" method="POST" action="customer.php">
        <div class="form-row">
            <div class="form-group">
                <label for="customer_id">Customer ID</label>
                <input type="text" id="customer_id" name="customer_id" placeholder="Customer ID" required>
            </div>
            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" placeholder="Customer Name" required>
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
                <input type="email" id="email" name="email" placeholder="Email" pattern="^[a-zA-Z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Please enter a valid email address (eg. example@example.com)" required>
            </div>
            <div class="form-group">
                <label for="gstin">GSTIN</label>
                <input type="text" id="gstin" name="gstin" placeholder="GSTIN" required>
            </div>
        </div>
        <input type="submit" id="submitButton" value="Add Customer">
    </form>
</div>

<?php
if ($result->num_rows > 0) {
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Customer ID</th>';
    echo '<th>Customer Name</th>';
    echo '<th>Address</th>';
    echo '<th>Phone</th>';
    echo '<th>Email</th>';
    echo '<th>GSTIN</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row["customer_id"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["customer_name"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["address"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["phone"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["email"]) . '</td>';
        echo '<td>' . htmlspecialchars($row["gstin"]) . '</td>';
        echo '<td class="button-container">';
        
        // Edit Button
        echo '<button class="edit-button" onclick="openEditModal(\'' . htmlspecialchars($row["customer_id"]) . '\', \'' . htmlspecialchars($row["customer_name"]) . '\', \'' . htmlspecialchars($row["address"]) . '\', \'' . htmlspecialchars($row["phone"]) . '\', \'' . htmlspecialchars($row["email"]) . '\', \'' . htmlspecialchars($row["gstin"]) . '\')">Edit</button>';
        
        // Delete Button
        echo '<form action="delete_customer.php" method="POST" style="display: inline; margin-left: 5px;">';
        echo '<input type="hidden" name="customer_id" value="' . htmlspecialchars($row["customer_id"]) . '">';
        echo '<button type="submit" class="delete-button" onclick="return confirm(\'Are you sure you want to delete this customer?\')">Delete</button>';
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

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h2>Edit Customer</h2>
    <form action="update_customer.php" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="edit_customer_id">Customer ID</label>
                <input type="text" id="edit_customer_id" name="customer_id" readonly>
            </div>
            <div class="form-group">
                <label for="edit_customer_name">Customer Name</label>
                <input type="text" id="edit_customer_name" name="customer_name" required>
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
                <input type="email" id="edit_email" name="email" pattern="^[a-zA-Z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Please enter a valid email address (eg. example@example.com)" required>
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
