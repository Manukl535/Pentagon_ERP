<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to handle SQL errors
function handle_sql_error($conn) {
    echo "Error: " . $conn->error;
    exit(); // Stop further execution
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign'])) {
    // Sanitize inputs
    $dn_number = sanitize_input($_POST['bin']);
    $associate = sanitize_input($_POST['associate']);

    // Validate inputs (basic validation)
    if (empty($dn_number) || empty($associate)) {
        echo "<script>alert('Please select both DN and Associate'); window.location.href = 'assign.php';</script>";
        exit(); // Stop further execution
    }

    // Prepare SQL statement to update dn_details table
    $update_sql = "UPDATE dn_details SET assigned_to = ? WHERE dn_number = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $associate, $dn_number);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "<script>alert('DN assigned successfully'); window.location.href = 'assign.php';</script>";
    } else {
        handle_sql_error($conn);
    }
}

// Fetch DN options for dropdown
$sql_dns = "SELECT dn_number FROM dn_details WHERE assigned_to = ''";
$result_dns = $conn->query($sql_dns);

// Fetch associate options for dropdown
$sql_associates = "SELECT username FROM associates";
$result_associates = $conn->query($sql_associates);

// Fetch data for the table
$sql_table = "SELECT dn_number, assigned_to, dn_quantity, picked_quantity, approved FROM dn_details";
$result_table = $conn->query($sql_table);


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign for picking</title>
<style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    padding: 20px;
    background-color: #f0f0f0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h2 {
    color: #333;
    text-align: center;
}

form {
    max-width: 400px;
    margin: 0 auto;
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

form label {
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}

form select {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}

form input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    box-sizing: border-box;
}

form input[type="submit"]:hover {
    background-color: #45a049;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

table th {
    background-color: #4CAF50;
    color: #fff;
}

table td button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

table td button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
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
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    position: relative;
}

.modal-content .close {
    color: #aaa;
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.modal-content h3 {
    text-align: center;
    margin-bottom: 20px;
}

.modal-content label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

.modal-content input[type="text"],
.modal-content input[type="text"]:disabled {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}

.modal-content input[type="text"]:disabled {
    background-color: #f0f0f0;
}

.modal-content button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.modal-content button:hover {
    background-color: #45a049;
}
table td button.approved {
    background-color: #f44336; /* Red color */
}
</style>
</head>
<body>
<div class="container">
    <h2>Assign Order</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="bin">Delivery Note:</label>
        <select id="bin" name="bin">
            <option value="">Select DN</option>
            <?php
            if ($result_dns->num_rows > 0) {
                while($row = $result_dns->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['dn_number']) . "'>" . htmlspecialchars($row['dn_number']) . "</option>";
                }
            } else {
                echo "<option value=''>No DN found</option>";
            }
            ?>
        </select>

        <label for="associate">Assign To:</label>
        <select id="associate" name="associate">
            <option value="">Select Associate</option>
            <?php
            if ($result_associates->num_rows > 0) {
                while ($row = $result_associates->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['username']) . "'>" . htmlspecialchars($row['username']) . "</option>";
                }
            } else {
                echo "<option value=''>No associates found</option>";
            }
            ?>
        </select>
        
        <input type="submit" name="assign" value="Assign">
    </form>

    <table>
        <thead>
            <tr>
                <th>DN</th>
                <th>Assigned To</th>
                <th>DN Qty</th>
                <th>Picked Qty</th>
                <th>Action</th> 
            </tr>
        </thead>
        <tbody>
        <?php
if ($result_table->num_rows > 0) {
    while ($row = $result_table->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['dn_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['assigned_to']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dn_quantity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['picked_quantity']) . "</td>";
        
        // Check if approved and set appropriate button state
        if (!empty($row['assigned_to']) && $row['picked_quantity'] == $row['dn_quantity']) {
            // Check if it's already approved
            $approved_class = ($row['approved'] == 1) ? 'approved' : '';

            echo "<td><button type='button' class='$approved_class' onclick=\"openModal('" . htmlspecialchars($row['dn_number']) . "', '" . htmlspecialchars($row['assigned_to']) . "', '" . htmlspecialchars($row['dn_quantity']) . "')\" " . ($row['approved'] == 1 ? 'disabled' : '') . ">Approve</button></td>";
        } else {
            echo "<td><button type='button' disabled>Approve</button></td>";
        }
        
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
}
?>


        </tbody>
    </table>

    <?php
    // Close database connection
    $conn->close();
    ?>

   <!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('myModal')">&times;</span>
        <h3>Approve Delivery Note</h3>
        <form id="approveForm" action="process_approve.php" method="post">
            <input type="hidden" id="modal_dn_number" name="dn_number">
            <label for="modal_assigned_to">Assigned To:</label>
            <input type="text" id="modal_assigned_to" name="assigned_to" readonly>
            <label for="modal_dn_quantity">DN Quantity:</label>
            <input type="text" id="modal_dn_quantity" name="dn_quantity" readonly>
            <label for="modal_po_number">PO Number:</label>
            <input type="text" id="modal_po_number" name="po_number" readonly>
            <label for="modal_customer_name">Customer Name:</label>
            <input type="text" id="modal_customer_name" name="customer_name" readonly>
            <label for="modal_address">Address:</label>
            <input type="text" id="modal_address" name="address" readonly>
            <label for="modal_phone">Phone:</label>
            <input type="text" id="modal_phone" name="phone" readonly>
            <label for="modal_email">Email:</label>
            <input type="text" id="modal_email" name="email" readonly>
            <label for="modal_gstin">GSTIN:</label>
            <input type="text" id="modal_gstin" name="gstin" readonly>
            <label for="approved_by">Approved By:</label>
            <input type="text" id="approved_by" name="approved_by">
            <button type="button" onclick="approveAndCloseModal()">Approve</button>
        </form>
    </div>
</div>



<script>
function openModal(dn_number, assigned_to, dn_quantity) {
    document.getElementById("modal_dn_number").value = dn_number;
    document.getElementById("modal_assigned_to").value = assigned_to;
    document.getElementById("modal_dn_quantity").value = dn_quantity;
    document.getElementById("approved_by").value = ""; // Clear approved_by field
    
    // AJAX call to fetch po_number and additional fields based on dn_number
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                document.getElementById("modal_po_number").value = response.po_number;
                document.getElementById("modal_customer_name").value = response.customer_name;
                document.getElementById("modal_address").value = response.address;
                document.getElementById("modal_phone").value = response.phone;
                document.getElementById("modal_email").value = response.email;
                document.getElementById("modal_gstin").value = response.gstin;
            } else {
                console.error('Error fetching PO Number');
            }
        }
    };
    xhr.open("GET", "fetch_po_number.php?dn_number=" + encodeURIComponent(dn_number), true);
    xhr.send();
    
    document.getElementById("myModal").style.display = "block";
}

function approveAndCloseModal() {
    // Validate and submit form
    var approved_by = document.getElementById("approved_by").value;
    if (approved_by.trim() === "") {
        alert("Please provide the name of the approver.");
        return;
    }
    
    // Submit form via AJAX or directly if no asynchronous action needed
    document.getElementById("approveForm").submit();
}
</script>



</body>
</html>
