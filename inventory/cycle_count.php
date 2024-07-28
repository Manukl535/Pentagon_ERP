<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Handle form submission for inserting data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign'])) {
    // Retrieve posted values
    $bin = $_POST['bin'];
    $associate = $_POST['associate'];

    // Check if both dropdowns are selected
    if (!empty($bin) && !empty($associate)) {
        // Generate CC ID
        $cc_id = generateCCID($bin);

        // Check if CC ID already exists
        $sql_check = "SELECT cc_id FROM cc_data WHERE cc_id = '$cc_id'";
        $result_check = $conn->query($sql_check);
        
        if ($result_check->num_rows > 0) {
            echo "<script>alert('This location is already assigned for cycle count.'); window.location.href = 'cycle_count.php';</script>";
        } else {
            // Insert data into cc_data
            $sql_insert = "INSERT INTO cc_data (cc_id, location, available_qty, associate_name) 
                           VALUES ('$cc_id', '$bin', (SELECT available_quantity FROM inv_location WHERE location='$bin'), '$associate')";
            
            if ($conn->query($sql_insert) === TRUE) {
                echo "<script>alert('Cycle count assigned successfully'); window.location.href = 'cycle_count.php';</script>"; 
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        }
    } else {
        echo "<script>alert('Please select both Location and Associate');</script>";
    }
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    // Retrieve cc_id to delete
    $cc_id = $_POST['cc_id'];

    // Delete data from cc_data
    $sql_delete = "DELETE FROM cc_data WHERE cc_id='$cc_id'";
    
    if ($conn->query($sql_delete) === TRUE) {
        echo "<script>alert('Cycle count deleted successfully'); window.location.href = 'cycle_count.php';</script>"; 
    } else {
        echo "Error: " . $sql_delete . "<br>" . $conn->error;
    }
}

// Function to generate CC ID
function generateCCID($location) {
    // Get the location prefix (e.g., 'A01', 'A02', ..., 'Z06')
    $location_prefix = strtoupper($location);

    // Get today's date components
    $day = date('d');
    $year = date('y'); // 2-digit year

    // Format the CC ID
    $cc_id = $location_prefix . sprintf('%02d', $day) . $year;
    
    return $cc_id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Bin for Cycle Count</title>
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
    background-color: #f2f2f2;
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
  <h2>Cycle Count</h2>
      
  <form action="#" method="post" style="    max-width: 400px;
    margin: 0 auto;
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <label for="bin">Location:</label>
    <select id="bin" name="bin">
      <option value="">Select Location</option>
      <?php
      // Query to fetch bin locations with available_qty > 0
      $sql = "SELECT location FROM inv_location WHERE available_quantity > 0";
      $result = $conn->query($sql);
      
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<option value='" . htmlspecialchars($row['location']) . "'>" . htmlspecialchars($row['location']) . "</option>";
        }
      } else {
        echo "<option value=''>No locations found</option>";
      }
      ?>
    </select>

    <label for="associate">Assign To:</label>
    <select id="associate" name="associate">
      <option value="">Select Associate</option>
      <?php
      // Query to fetch associates
      $sql = "SELECT username FROM associates";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
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
        <th>CC ID</th>
        <th>Location</th>
        <th>Location Qty</th>
        <th>Assign To</th>
        <th>Scanned Qty</th>
        <th>Difference</th>
        <th>Location Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Query to fetch bin data from cc_data
      $sql = "SELECT cc_id, location, available_qty, associate_name, scanned_qty FROM cc_data";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['cc_id']) . "</td>";
          echo "<td>" . htmlspecialchars($row['location']) . "</td>";
          echo "<td>" . htmlspecialchars($row['available_qty']) . "</td>";
          echo "<td>" . htmlspecialchars($row['associate_name']) . "</td>";
          echo "<td>" . htmlspecialchars($row['scanned_qty']) . "</td>";
          // Calculate available quantity minus scanned quantity
          $available_qty = intval($row['available_qty']);
          $scanned_qty = intval($row['scanned_qty']);
          $remaining_qty = $available_qty - $scanned_qty;
          echo "<td>" . $remaining_qty . "</td>";
          // Assuming 'Normal' is static; otherwise, calculate based on conditions
          echo "<td>Normal</td>";
          echo "<td>
                  <form action='#' method='post' style='display:inline;'>
                      <input type='hidden' name='cc_id' value='" . htmlspecialchars($row['cc_id']) . "'>
                      <input type='submit' name='delete' value='Delete'>
                  </form>
                </td>";
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='8'>No Works Found</td></tr>";
      }

      $conn->close();
      ?>
    </tbody>
  </table>

</body>
</html>
