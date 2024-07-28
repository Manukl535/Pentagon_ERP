<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Assume $loggedInEmail holds the logged-in user's email
$loggedInEmail = 'inventory@pentagon.com'; // Replace with actual logged-in user's email

// Determine the department based on the logged-in user's email
if ($loggedInEmail == 'inventory@pentagon.com') {
    $departmentFilter = "AND dept = 'inventory'";
} else {
    // Default to select all departments
    $departmentFilter = "";
}

$sql = "SELECT * FROM safety_reports WHERE 1 $departmentFilter";
$result = mysqli_query($conn, $sql);

$safety_reports = []; // Initialize an empty array to store fetched data

while ($row = mysqli_fetch_assoc($result)) {
    $safety_reports[] = $row; // Append each row to the $safety_reports array
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Safety Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #45a049;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e9e9e9;
        }
    </style>
</head>
<body>
<a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
    <h2>Safety Report</h2>
    <table>
        
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Department</th>
                <th>Description</th>
                <th>Reported By</th>
                <th>Reported On</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1; // Initialize a counter for serial numbers
            foreach ($safety_reports as $safety_report) {
                echo "<tr>";
                echo "<td>" . $count++ . "</td>"; // Display and increment the count
                echo "<td>" . htmlspecialchars(ucfirst($safety_report['dept'])) . "</td>"; 
                echo "<td>" . htmlspecialchars($safety_report['issue']) . "</td>";
                echo "<td>" . htmlspecialchars($safety_report['name']) . "</td>";
                echo "<td>" . htmlspecialchars($safety_report['created_at']) . "</td>";
                echo "</tr>";
            }
            ?>
            

        </tbody>
    </table>
</body>
</html>
