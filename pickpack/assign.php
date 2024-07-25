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
$sql_table = "SELECT dn_number, assigned_to, dn_quantity, picked_quantity FROM dn_details";
$result_table = $conn->query($sql_table);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Assign for picking</title>
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
<h2>Assign Order</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="max-width: 400px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
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
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
