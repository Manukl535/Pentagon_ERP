<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bin Transfer Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .form-row .form-group {
            flex-basis: calc(50% - 10px); /* Adjust width of each form group */
        }
        .form-row .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-row .form-group input, .form-row .form-group select {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
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
    <div class="container">
        <h2 style="text-align: center">Bin Transfer</h2>
        <form id="binTransferForm" action="process_bin_transfer.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="location">Location:</label>
                    <select id="location" name="location" required>
                    <option value="">Select Location</option>
                        <?php
                        // Assuming $conn is your database connection
                        include('../includes/connection.php'); // Include your database connection file

                        // Fetch locations from inv_location table
                        $query = "SELECT location FROM inv_location";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['location'] . '">' . $row['location'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No locations found</option>';
                        }

                        // Close database connection
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="article_number">Article Number:</label>
                    <input type="text" id="article_number" name="article_number" required readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="1" required readonly>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" required readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="color">Color:</label>
                    <input type="text" id="color" name="color" required readonly>
                </div>
                <div class="form-group">
                    <label for="size">Size:</label>
                    <input type="text" id="size" name="size" required readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" required readonly>
                </div>
                <div class="form-group">
    <label for="destination_bin">Destination Bin:</label>
    <select id="destination_bin" name="destination_bin" required>
        <option value="">Select Destination Bin</option>
        <!-- Options will be dynamically populated by JavaScript -->
    </select>
</div>

            </div>
            <input type="submit" value="Transfer">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    $('#location').change(function() {
        var location = $(this).val();
        $.ajax({
            url: 'bin_transfer_handler.php',
            method: 'POST',
            dataType: 'json',
            data: { location: location },
            success: function(data) {
                $('#article_number').val(data.article_number);
                $('#quantity').val(data.quantity);
                $('#description').val(data.description);
                $('#color').val(data.color);
                $('#size').val(data.size);
                $('#category').val(data.category);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ' + status + ' - ' + error);
            }
        });
    });
});

    </script>
</body>
</html>
