<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>putaway Table</title>
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
            padding: 10px;
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
            border: none;
            background: none;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <h2>Assign Work For Putaway</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>SI. No</th>
                    <th>PO Number</th>
                    <th>Associate Name</th>
                    <th>Action</th>
                    <th>PO Quantity</th>
                    <th>Processed Quantity</th>
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

                $rows = 10; // Number of rows you want in the table
                for ($i = 1; $i <= $rows; $i++) {
                    // Random quantities for demonstration
                    $poQuantity = rand(1, 100);
                    $processedQuantity = rand(0, $poQuantity);

                    echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . "PO" . sprintf("%03d", $i) . "</td>";
                    echo "<td class='associate-cell'>";
                    echo "<select>";
                    echo "<option value=''>Select Associate</option>";
                    foreach ($associates as $associate) {
                        echo "<option value='" . $associate['id'] . "'>" . $associate['username'] . "</option>";
                    }
                    echo "</select>";
                    echo "</td>";
                    echo "<td><button class='assign-button' onclick='assignButtonClick(this)'>Assign</button></td>";
                    echo "<td class='po-quantity'>" . $poQuantity . "</td>";
                    echo "<td class='processed-quantity'>" . $processedQuantity . "</td>";
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

            // Update the Processed Quantity to 0
            const processedQuantityCell = row.querySelector('.processed-quantity');
            processedQuantityCell.textContent = '0'; 

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
