<?php
// Database connection details
include('../Includes/connection.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_id"])) {
        // Handle delete request
        $delete_id = $_POST["delete_id"];
        $stmt = $conn->prepare("DELETE FROM vendors WHERE id=?");
        $stmt->bind_param("i", $delete_id);
        
        if ($stmt->execute()) {
            $alertMessage = "Record successfully deleted.";
        } else {
            $alertMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Handle add/update request
        $id = $_POST["id"];
        $name = $_POST["name"];
        $address = $_POST["address"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $gst = $_POST["gst"];
        $row_index = $_POST["row_index"];

        if (!empty($row_index)) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE vendors SET id=?, name=?, address=?, phone=?, email=?, gst=? WHERE id=?");
            $stmt->bind_param("ssssssi", $id, $name, $address, $phone, $email, $gst, $row_index);
        } else {
            // Add new record
            $stmt = $conn->prepare("INSERT INTO vendors (id, name, address, phone, email, gst) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $id, $name, $address, $phone, $email, $gst);
        }

        if ($stmt->execute()) {
            if (!empty($row_index)) {
                // Record updated successfully
                $alertMessage = "Record successfully updated.";
            } else {
                // Record added successfully
                $alertMessage = "Record successfully added.";
            }
        } else {
            $alertMessage = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: vendors.php?alertMessage=" . urlencode($alertMessage));
    exit();
}

// Retrieve vendor data
$result = $conn->query("SELECT * FROM vendors");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Details</title>
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
            display:inline; 
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
        .form-group {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: 10px;
        }
    </style>
    <script>
    function editVendor(row) {
        var cells = row.getElementsByTagName("td");
        document.getElementById("row_index").value = row.dataset.id;
        document.getElementById("id").value = cells[0].innerHTML;
        document.getElementById("name").value = cells[1].innerHTML;
        document.getElementById("address").value = cells[2].innerHTML;
        document.getElementById("phone").value = cells[3].innerHTML;
        document.getElementById("email").value = cells[4].innerHTML;
        document.getElementById("gst").value = cells[5].innerHTML;
        document.getElementById("submitButton").innerText = "Update Vendor"; // Change button text to "Update Vendor"
    }

    function deleteVendor(row) {
        var rowIndex = row.dataset.id; // Use data-id attribute for the row index
        if (confirm("Are you sure you want to delete this record?")) {
            var form = document.createElement("form");
            form.method = "POST";
            form.action = "vendors.php";

            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "delete_id";
            input.value = rowIndex;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</head>
<body>

<?php if (isset($_GET['alertMessage'])): ?>
    <script>alert('<?php echo $_GET['alertMessage']; ?>');</script>
<?php endif; ?>

<div class="form-container">
    <form id="vendorForm" method="POST" action="vendors.php">
        <div class="form-group">
            <input type="hidden" id="row_index" name="row_index">
            <label for="id">Vendor ID</label>
            <input type="text" id="id" name="id" placeholder="Vendor ID" required>
            <label for="name">Vendor Name</label>
            <input type="text" id="name" name="name" placeholder="Vendor Name" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Address" required>
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" placeholder="Phone" pattern="[0-9]{10}" title="Please enter 10 digits" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" pattern="^[a-zA-Z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Please enter a valid email address (e.g. example@example.com)" required>
            <label for="gst">GSTIN</label>
            <input type="text" id="gst" name="gst" placeholder="GSTIN" required>
        </div>
        <button id="submitButton" type="submit">Add Vendor</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Vendor ID</th>
            <th>Vendor Name</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Email</th>
            <th>GST</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="vendorTableBody">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr data-id="<?php echo $row["id"]; ?>"> <!-- Add data-id attribute for row index -->
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["address"]; ?></td>
                    <td><?php echo $row["phone"]; ?></td>
                    <td><?php echo $row["email"]; ?></td>
                    <td><?php echo $row["gst"]; ?></td>
                    <td>
                        <button class="action-button edit-button" onclick="editVendor(this.parentElement.parentElement)">Edit</button>
                        <button class="action-button delete-button" onclick="deleteVendor(this.parentElement.parentElement)">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No vendors found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

        </body>
</html>
