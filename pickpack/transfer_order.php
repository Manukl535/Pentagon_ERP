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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="UTF-8">
    <title>Goods Order Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .order-form {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            position: relative; /* Ensure relative positioning */
        }
        form {
            display: grid;
            gap: 15px;
        }
        .row {
            display: flex;
            gap: 15px; /* Adjust as needed */
            align-items: center; /* Align items vertically */
            position: relative; /* Needed for button alignment */
        }
        .row label {
            flex: 1; /* Distribute equal width among labels */
        }
        .row label span {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="email"],
        select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="date"] {
            width: 100%;
            padding: 8px;
            font-size: 13px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .center {
            text-align: center;
            margin-top: 10px; /* Added margin for better spacing */
            position: absolute; /* Position absolutely within .order-form */
            bottom: 20px; /* Adjust as per your design preference */
            left: 50%;
            transform: translateX(-50%);
        }
        /* Styling for the modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        .delete-button {
            font-size: 14px; 
            padding: 6px 10px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
    <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
        <div class="title">
            <h2>Transfer Order</h2>
        </div>
        <div class="order-form">
            <form action="submit_order.php" method="post">
                <div class="row">
                    <label>
                        <span>Customer Name:</span>
                        <select name="customer_name" id="customer_name" required onchange="fetchCustomerDetails()">
                            <option value="">Select Customer</option>
                            <!-- PHP code to fetch customers from database -->
                            <?php
                            // Include the database connection
                            include('../includes/connection.php');

                            // Fetch customers from the database
                            $query = "SELECT customer_name FROM pp_customer";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['customer_name']) . '">' . htmlspecialchars($row['customer_name']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No customers found</option>';
                            }

                            // Close the database connection
                            $conn->close();
                            ?>
                        </select>
                    </label>
                    <label>
                        <span>Address:</span>
                        <input type="text" name="address" id="address" required readonly>
                    </label>
                    <label>
                        <span>Phone:</span>
                        <input type="text" name="phone" id="phone" required readonly>
                    </label>
                </div>
                <div class="row">
                    <label>
                        <span>Email:</span>
                        <input type="email" name="email" id="email" required readonly>
                    </label>
                    <label>
                        <span>GST:</span>
                        <input type="text" name="gst" id="gst" required readonly>
                    </label>
                    <label>
                        <span>Date of Delivery:</span>
                        <?php
                            // Calculate 1 week ahead date
                            $minDate = date('Y-m-d', strtotime('+1 week'));
                        ?>
                        <input type="date" name="date_of_delivery" id="date_of_delivery" min="<?php echo $minDate; ?>" required>
                    </label>
                </div>
                <div class="row">
                    <label>
                        <span>Article No:</span>
                        <select name="article_no" id="article_no" required onchange="fetchArticleDetails(this.value, this.parentNode.parentNode)">
                            <option value="">Select Article No</option>
                            <!-- PHP code to fetch article numbers from database -->
                            <?php
                            // Include the database connection
                            include('../includes/connection.php');

                            // Fetch article numbers from the database
                            $query = "SELECT DISTINCT article_no FROM inv_location";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($row['article_no']) . '">' . htmlspecialchars($row['article_no']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No articles found</option>';
                            }

                            // Close the database connection
                            $conn->close();
                            ?>
                        </select>
                    </label>
                    <label>
                        <span>Color:</span>
                        <select name="color" id="color" required>
                            <option value="">Select Color</option>
                        </select>
                    </label>
                    <label>
                        <span>Size:</span>
                        <select name="size" id="size" required>
                            <option value="">Select Size</option>
                        </select>
                    </label>
                    <label>
                        <span>Quantity:</span>
                        <input type="number" name="quantity" id="quantity" min="1" required>
                    </label>
                </div>
                <div style="text-align: center;">
                    <button type="submit">Submit Order</button>
                </div>
                <!-- Hidden input to store the total number of dynamic rows -->
                <input type="hidden" id="dynamicRowsCount" name="dynamicRowsCount" value="0">
            </form>
            <br><br><br>
            <div class="center">
                <button type="button" onclick="openModal()">Add More Items</button>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Enter number of items to add:</p>
            <input type="number" id="quantityInput" min="1"><br><br>
            <button onclick="addItems()">Submit</button>
        </div>
    </div>

    <script>
        function fetchCustomerDetails() {
            var customerName = document.getElementById('customer_name').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_customer_details.php?customer_name=' + encodeURIComponent(customerName), true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data.hasOwnProperty('error')) {
                        console.error('Error fetching customer details:', data.error);
                        // Optionally clear fields if no customer found
                        document.getElementById('address').value = '';
                        document.getElementById('phone').value = '';
                        document.getElementById('email').value = '';
                        document.getElementById('gst').value = '';
                    } else {
                        document.getElementById('address').value = data.address;
                        document.getElementById('phone').value = data.phone;
                        document.getElementById('email').value = data.email;
                        document.getElementById('gst').value = data.gstin;
                    }
                } else {
                    console.error('Request failed. Status: ' + xhr.status);
                }
            };
            xhr.send();
        }

        function fetchArticleDetails(articleNo, container) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_article_details.php?article_no=' + encodeURIComponent(articleNo), true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    populateSelect(container.querySelector('#color'), data.colors);
                    populateSelect(container.querySelector('#size'), data.sizes);
                }
            };
            xhr.send();
        }

        // Helper function to populate select options
        function populateSelect(select, options) {
            select.innerHTML = '<option value="">Select ' + select.id.charAt(0).toUpperCase() + select.id.slice(1) + '</option>';
            options.forEach(function(option) {
                var optionElem = document.createElement('option');
                optionElem.value = option;
                optionElem.textContent = option;
                select.appendChild(optionElem);
            });
        }

        function openModal() {
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        function addItems() {
            var quantity = document.getElementById('quantityInput').value;
            var articleSelect = document.getElementById('article_no');
            var colorSelect = document.getElementById('color');
            var sizeSelect = document.getElementById('size');
            var quantityInput = document.getElementById('quantity');

            var form = document.querySelector('form');
            var submitButton = form.querySelector('button[type="submit"]');
            var container = form.querySelector('.center');

            for (var i = 0; i < quantity; i++) {
                // Create a new row
                var row = document.createElement('div');
                row.classList.add('row');

                // Article No
                var articleClone = articleSelect.cloneNode(true);
                articleClone.removeAttribute('id');
                articleClone.removeAttribute('onchange');
                row.appendChild(articleClone);

                // Color
                var colorClone = colorSelect.cloneNode(true);
                colorClone.removeAttribute('id');
                row.appendChild(colorClone);

                // Size
                var sizeClone = sizeSelect.cloneNode(true);
                sizeClone.removeAttribute('id');
                row.appendChild(sizeClone);

                // Quantity
                var quantityClone = quantityInput.cloneNode(true);
                quantityClone.removeAttribute('id');
                row.appendChild(quantityClone);

                // Delete button
                var deleteButton = document.createElement('button');
                deleteButton.innerHTML = 'X';
                deleteButton.classList.add('delete-button');
                deleteButton.type = 'button'; // Ensure it doesn't submit the form
                deleteButton.onclick = (function (currentRow) {
                    return function() {
                        currentRow.remove();
                    };
                })(row); // Immediately invoked function to capture current row

                row.appendChild(deleteButton);

                // Insert the new row above the existing submit button
                form.insertBefore(row, submitButton.parentNode);

                // Add a margin for spacing between rows
                row.style.marginBottom = '15px';

                // Fetch article details for the newly added row
                fetchArticleDetails(articleClone.value, row);
            }

            // Update the hidden input value with the current count of dynamic rows
            document.getElementById('dynamicRowsCount').value = document.querySelectorAll('.row').length - 1;

            // Close the modal and reset the quantity input
            closeModal();
        }

        // Function to check for success parameter in URL and display alert
        function displayAlert() {
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === '1') {
                alert("Order has been transferred. Ready for picking.");
            }
        }

        // Execute the function on page load
        window.onload = function() {
            displayAlert();
        };
    </script>
</body>
</html>
