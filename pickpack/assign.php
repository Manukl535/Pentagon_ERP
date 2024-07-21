<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Table</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
            margin: 0;
        }
        .table-container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .assign-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .assign-button:hover {
            background-color: #45a049;
        }
        .assigned-button {
            background-color: #FF9800;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>SI. No</th>
                    <th>DN Number</th>
                    <th>Associate Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                session_start();
                include('../includes/connection.php');
                
                $sql = "SELECT * FROM associates";
                $result = $conn->query($sql);
                $associates = [];
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $associates[] = $row;
                    }
                }

                $rows = 5; // Number of rows you want in the table
                for ($i = 1; $i <= $rows; $i++) {
                    echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . "DN" . sprintf("%03d", $i) . "</td>";
                    echo "<td>";
                    echo "<select>";
                    foreach ($associates as $associate) {
                        echo "<option value='" . $associate['id'] . "'>" . $associate['username'] . "</option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td><button class='assign-button' onclick='assignButtonClick(this)'>Assign</button></td>";
                    echo "</tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function assignButtonClick(button) {
            button.textContent = 'Assigned';
            button.className = 'assigned-button';
        }
    </script>
</body>
</html>
