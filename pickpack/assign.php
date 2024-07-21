<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Work</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 90vh;
            margin: 0;
            padding: 10px;
        }
        h2 {
            margin-bottom: 10px;
        }
        .table-container {
            width: 80%;
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
            padding: 8px;
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
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .assign-button:hover {
            background-color: #45a049;
        }
        .assigned-button {
            background-color: red;
            color: white;
            border: none;
            padding: 6px 12px;
            cursor: not-allowed;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .associate-cell {
            position: relative;
        }
        select {
            width: 100%;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            appearance: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        select:hover {
            background-color: #f1f1f1;
        }
        .assigned-select {
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h2>Assign Work</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>SI. No</th>
                    <th>DN Number</th>
                    <th>Associate</th>
                    <th>Actions</th>
                    <th>DN Quantity</th>
                    <th>Picked Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                session_start();
                include('../includes/connection.php');
                
                // Fetch DN Numbers from dn_details table
                $sql_dn = "SELECT dn_number FROM dn_details";
                $result_dn = $conn->query($sql_dn);
                $dn_numbers = [];
                if ($result_dn->num_rows > 0) {
                    while ($row_dn = $result_dn->fetch_assoc()) {
                        $dn_numbers[] = $row_dn['dn_number'];
                    }
                }

                // Fetch Associates from associates table
                $sql_assoc = "SELECT id, username FROM associates";
                $result_assoc = $conn->query($sql_assoc);
                $associates = [];
                if ($result_assoc->num_rows > 0) {
                    while ($row_assoc = $result_assoc->fetch_assoc()) {
                        $associates[] = $row_assoc;
                    }
                }

                $rows = count($dn_numbers); // Number of rows based on DN Numbers fetched
                for ($i = 0; $i < $rows; $i++) {
                    // Random quantities for demonstration
                    $dnQuantity = rand(1, 100);
                    $pickedQuantity = rand(0, $dnQuantity);

                    echo "<tr>";
                    echo "<td>" . ($i + 1) . "</td>";
                    echo "<td>" . $dn_numbers[$i] . "</td>";
                    echo "<td class='associate-cell'>";
                    echo "<select>";
                    echo "<option value=''>Select Associate</option>";
                    foreach ($associates as $associate) {
                        echo "<option value='" . $associate['id'] . "'>" . $associate['username'] . "</option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td><button class='assign-button' onclick='assignButtonClick(this)'>Assign</button></td>";
                    echo "<td class='dn-quantity'>" . $dnQuantity . "</td>";
                    echo "<td class='picked-quantity'>" . $pickedQuantity . "</td>";
                    echo "</tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function assignButtonClick(button) {
            const row = button.closest('tr');
            const select = row.querySelector('select');
            const selectedValue = select.value;
            const selectedOption = select.options[select.selectedIndex].text;

            if (selectedValue === '') {
                alert('Please select an associate before assigning.');
                return;
            }

            // Update the Picked Quantity to 0
            const pickedQuantityCell = row.querySelector('.picked-quantity');
            pickedQuantityCell.textContent = '0'; 

            // Disable the select and show only the assigned name
            const assignedSelect = document.createElement('div');
            assignedSelect.textContent = selectedOption;
            assignedSelect.className = 'assigned-select';

            select.replaceWith(assignedSelect);

            // Change button appearance
            button.textContent = 'Assigned';
            button.className = 'assigned-button'; // Ensure class is correctly applied
            button.disabled = true; // Optionally disable the button after assignment
        }
    </script>
</body>
</html>
