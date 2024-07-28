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
        echo "<script>alert('Please select both PO and Associate'); window.location.href = 'putaway.php';</script>";
        exit(); // Stop further execution
    }

    // Check if the PO is already assigned
    $sql_check_assigned = "SELECT * FROM putaway WHERE po = ?";
    $stmt_check_assigned = $conn->prepare($sql_check_assigned);
    $stmt_check_assigned->bind_param("s", $dn_number);
    $stmt_check_assigned->execute();
    $result_check_assigned = $stmt_check_assigned->get_result();

    if ($result_check_assigned->num_rows > 0) {
        echo "<script>alert('This PO is already assigned.'); window.location.href = 'putaway.php';</script>";
        exit(); // Stop further execution
    }

    // Fetch PO details from approved_po table
    $sql_fetch_po_details = "SELECT po_number, article, size, quantity, color FROM approved_po WHERE po_number = ?";
    $stmt_fetch_po_details = $conn->prepare($sql_fetch_po_details);
    $stmt_fetch_po_details->bind_param("s", $dn_number);
    $stmt_fetch_po_details->execute();
    $result_fetch_po_details = $stmt_fetch_po_details->get_result();

    if ($result_fetch_po_details->num_rows > 0) {
        // Insert into putaway table
        $row = $result_fetch_po_details->fetch_assoc();
        $article = $row['article'];
        $size = $row['size'];
        $quantity = $row['quantity'];
        $color = $row['color'];

        $sql_insert = "INSERT INTO putaway (po, article, size, po_quantity, color, assigned_to) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssss", $dn_number, $article, $size, $quantity, $color, $associate);

        if ($stmt_insert->execute()) {
            echo "<script>alert('PO assigned successfully');</script>";
        } else {
            handle_sql_error($conn);
        }

        // Close statement
        $stmt_insert->close();
    } else {
        echo "<script>alert('PO details not found in approved_po table');</script>";
    }

    // Close statement
    $stmt_fetch_po_details->close();
}

// Fetch data for the main table
$sql_main_table = "SELECT po, article, size, color, assigned_to, po_quantity, processed_qty FROM putaway";
$result_table = $conn->query($sql_main_table);

// Check if the query was successful
if (!$result_table) {
    handle_sql_error($conn);
}

// Fetch data from approved_po table for dropdown, excluding already assigned POs
$sql_dns = "SELECT po_number, article, size, quantity, color FROM approved_po WHERE po_number NOT IN (SELECT po FROM putaway)";
$result_dns = $conn->query($sql_dns);

// Check if the query was successful
if (!$result_dns) {
    handle_sql_error($conn);
}

// Fetch associate options for dropdown
$sql_associates = "SELECT username FROM associates";
$result_associates = $conn->query($sql_associates);

// Check if the query was successful
if (!$result_associates) {
    handle_sql_error($conn);
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Assign For Putaway</title>
<style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    padding: 20px;
}
h2 {
    color: #333;
    text-align:center;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}
th {
    background-color:  #4CAF50;
    color:#fff;
}
label {
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}
select {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    box-sizing: border-box;
}
input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}
input[type="submit"]:hover {
    background-color: #45a049;
}
</style>
</head>
<body>
<a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
<h2>Assign For Putaway</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="max-width: 400px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <label for="bin">PO:</label>
    <select id="bin" name="bin">
        <option value="">Select PO</option>
        <?php
        if ($result_dns->num_rows > 0) {
            while($row = $result_dns->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['po_number']) . "'>" . htmlspecialchars($row['po_number']) . " - " . htmlspecialchars($row['article']) . " - " . htmlspecialchars($row['size']) . " - " . htmlspecialchars($row['quantity']) . " - " . htmlspecialchars($row['color']) . "</option>";
            }
        } else {
            echo "<option value=''>No PO found</option>";
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
            <th>PO</th>
            <th>Article</th>
            <th>Size</th>
            <th>Color</th>
            <th>Assigned To</th>
            <th>PO Qty</th>
            <th>Processed Qty</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result_table->num_rows > 0) {
            while ($row = $result_table->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['po']) . "</td>";
                echo "<td>" . htmlspecialchars($row['article']) . "</td>";
                echo "<td>" . htmlspecialchars($row['size']) . "</td>";
                echo "<td>" . htmlspecialchars($row['color']) . "</td>";
                echo "<td>" . htmlspecialchars($row['assigned_to']) . "</td>";
                echo "<td>" . htmlspecialchars($row['po_quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row['processed_qty']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
