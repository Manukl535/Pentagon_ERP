<?php
// Include your database connection script
include('../includes/connection.php');

// Assume $loggedInEmail holds the logged-in user's email
$loggedInEmail = 'receipt@pentagon.com'; // Replace with actual logged-in user's email

// Determine the department based on the logged-in user's email
if ($loggedInEmail == 'receipt@pentagon.com') {
    $departmentFilter = "AND dept = 'receipt'";
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