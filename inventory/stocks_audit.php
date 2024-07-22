<?php
session_start();

include('../includes/connection.php');

// Handle form submission for inserting data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign'])) {
    // Retrieve posted values
    $bin = $_POST['bin'];
    $associate = $_POST['associate'];

    // Check if both dropdowns are selected
    if (!empty($bin) && !empty($associate)) {
        // Generate CC ID
        $audit_id = generateCCID($bin);

        // Check if location already exists in audit_log
        $sql_check = "SELECT audit_id FROM audit_log WHERE location = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $bin);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            // Location exists, update the record
            $sql_update = "UPDATE audit_log SET audit_id = ?, associate = ? WHERE location = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sss", $audit_id, $associate, $bin);

            if ($stmt_update->execute()) {
                echo "<script>alert('Audit assignment updated successfully'); window.location.href = 'stocks_audit.php';</script>";
            } else {
                echo "Error updating record: " . $stmt_update->error;
            }
        } else {
            // Location does not exist, insert new record
            $sql_insert = "INSERT INTO audit_log (audit_id, location, associate) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $audit_id, $bin, $associate);

            if ($stmt_insert->execute()) {
                echo "<script>alert('Audit assignment inserted successfully'); window.location.href = 'stocks_audit.php';</script>";
            } else {
                echo "Error inserting record: " . $stmt_insert->error;
            }
        }
    } else {
        echo "<script>alert('Please select both Location and Associate');</script>";
    }
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
  // Retrieve audit_id to delete
  $audit_id = $_POST['audit_id'];

  // Update data in audit_log
  $sql_delete = "UPDATE audit_log SET associate = NULL, audit_quantity = NULL, audit_id = NULL WHERE audit_id=?";
  $stmt_delete = $conn->prepare($sql_delete);
  $stmt_delete->bind_param("s", $audit_id);

  if ($stmt_delete->execute()) {
      echo "<script>alert('Audit data deleted successfully'); window.location.href = 'stocks_audit.php';</script>"; 
  } else {
      echo "Error deleting record: " . $stmt_delete->error;
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
    $audit_id = $location_prefix . sprintf('%02d', $day) . $year;
    
    return $audit_id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Assign Bin for Audit</title>
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
  <?php
  include('start_audit.php');
  ?>
  <h2>Stock Audit</h2>
  
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
      $sql = "SELECT location FROM audit_log WHERE qty_23_24 > 0";
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
        <th>Audit ID</th>
        <th>Assigned To</th>
        <th>Location</th>
        <th>Location Qty</th>
        <th>Scanned Qty</th>
        <th>Difference</th>
        <th>Location Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Example logic to fetch and display audit details
      $sql = "SELECT audit_id, associate, location, qty_23_24, audit_quantity as scanned_qty FROM audit_log WHERE qty_23_24 > 0";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row['audit_id']) . "</td>";
              echo "<td>" . htmlspecialchars($row['associate']) . "</td>";
              echo "<td>" . htmlspecialchars($row['location']) . "</td>";
              echo "<td>" . htmlspecialchars($row['qty_23_24']) . "</td>";
              echo "<td>" . htmlspecialchars($row['scanned_qty']) . "</td>";
              // Calculate difference if needed
              $difference = intval($row['qty_23_24']) - intval($row['scanned_qty']);
              echo "<td>" . $difference . "</td>";
              // Assuming 'Normal' is static; otherwise, calculate based on conditions
              echo "<td>Normal</td>";
              echo "<td>
                        <form action='#' method='post' style='display:inline;'>
                            <input type='hidden' name='audit_id' value='" . htmlspecialchars($row['audit_id']) . "'>
                            <input type='submit' name='delete' value='Delete'>
                        </form>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='8'>No audit data found</td></tr>";
      }
      ?>
    </tbody>
  </table>

</body>
</html>