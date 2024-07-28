<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
        <h2 style="text-align: center">Bin Transfer</h2>
        <form id="binTransferForm" action="bin_transfer_handler.php" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="location">Source Location:</label>
                    <select id="location" name="location" required>
                        <option value="">Select Location</option>
                        <?php
                        // Fetch locations from inv_location table
                        $query = "SELECT location FROM inv_location WHERE available_quantity > 0";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['location'] . '">' . $row['location'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No locations found</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="article_number">Article Number:</label>
                    <input type="text" id="article_number" name="article_no" required readonly>
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
                    <input type="text" id="size" name="article_size" required readonly>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" id="category" name="category" required readonly>
                </div>
                <div class="form-group">
                    <label for="destination_bin">Destination Location:</label>
                    <select id="destination_bin" name="destination_bin" required>
                        <option value="">Select Destination Bin</option>
                        <?php
                            // Fetch locations from inv_location table
                            $query1 = "SELECT location, available_quantity AS qty, capacity AS cap, article_size FROM inv_location";
                            $result1 = $conn->query($query1);

                            if ($result1->num_rows > 0) {
                                while ($row = $result1->fetch_assoc()) {
                                    $location = $row['location'];
                                    $qty = $row['qty'];
                                    $cap = $row['cap'];
                                    $article_size = $row['article_size'];

                                    if ($qty == 0) {
                                        echo '<option value="' . $location . '" data-available="false">' . $location . ' (Qty: 0)(Cap: ' . $cap . ')</option>';
                                    } else {
                                        // echo '<option value="' . $location . '" data-available="true">' . $location . ' (Qty: ' . $qty . ')(Cap: ' . $cap . ')</option>';
                                    }
                                }
                            } else {
                                echo '<option value="">No locations found</option>';
                            }
                        ?>
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
                url: 'bin_map.php',
                method: 'POST',
                dataType: 'json',
                data: { location: location },
                success: function(data) {
                    $('#article_number').val(data.article_number);
                    $('#quantity').val(data.quantity);
                    $('#description').val(data.description);
                    $('#color').val(data.color);
                    $('#size').val(data.article_size);
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

<?php
// Close database connection (if it's not already closed)
$conn->close();
?>
