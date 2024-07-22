<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Query to fetch locations from database
$query = "SELECT * FROM inv_location";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Inventory Stocks</title>
    <style>
           body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .main-content {
        margin: 20px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }

    h2, h3 {
        color: #333;
        text-align: center;
    }

    form {
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    form div {
        flex: 0 0 48%;
        padding: 10px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
    }

    input {
        width: 90%;
        padding: 8px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 50px;
    }

    button:hover {
        background-color: #45a049;
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
        background-color: #4caf50;
        color: #fff;
    }

    tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    a {
        color: #d9534f;
        text-decoration: none;
        cursor: pointer;
    }

    a:hover {
        text-decoration: underline;
    }

    .update-btn {
        background-color: #008CBA;
        color: #fff;
        padding: 5px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
    }

    .update-btn:hover {
        background-color: #00587a;
    }

    .update-row {
        display: none;
    }

    .editable-qty input {
        width: 60px;
        margin-right: 10px;
    }

    .export-btn {
        background-color: #337ab7;
        color: #fff;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .export-btn:hover {
        background-color: #286090;
    }
 
    </style>
</head>

<body>
<div class="main-content">
        <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>

        <div>
            <h2>Stock List</h2>
            <!-- Export to Excel button -->
            <button class="export-btn" onclick="exportToExcel('product-table')">Export to Excel</button>

            <table id="product-table">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Article</th>
                        <th>Description</th>
                        <th>Color</th>
                        <th>Available Quantity</th>
                        <th>Category</th>
                        <th>Size</th>
                        <!-- <th>Bin Status</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['capacity']; ?></td>
                            <td><?php echo $row['article_no']; ?></td>
                            <td><?php echo $row['article_description']; ?></td>
                            <td><?php echo $row['color']; ?></td>
                            <td><?php echo $row['available_quantity']; ?></td> 
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['article_size']; ?></td>
                            <!-- <td><?php echo $row['status']; ?></td> -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Function to export table data to Excel
        function exportToExcel(tableId) {
            var tab_text = "<table border='2px'><tr>";
            var textRange; var j = 0;
            tab = document.getElementById(tableId); // id of table

            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                //tab_text=tab_text+"</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to manu.xls");
            }
            else {
                sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            }

            return (sa);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var updateButtons = document.querySelectorAll('.update-btn');
            updateButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var productId = this.getAttribute('data-product-id');
                    var updateRow = document.querySelector('.update-row[data-product-id="' + productId + '"]');
                    updateRow.style.display = 'table-row';

                    // Add click event listener for the "Save" button in the update row
                    var saveButton = updateRow.querySelector('.update-btn');
                    saveButton.addEventListener('click', function () {
                        var newQuantityInput = updateRow.querySelector('input[name="available_qty"]');
                        var newQuantity = newQuantityInput.value;

                        // Send the updated quantity to the server using AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_quantity.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                // Handle the response from the server if needed
                                alert(xhr.responseText);
                                // Reload the page after updating the quantity
                                location.reload();
                            }
                        };
                        xhr.send('product_id=' + productId + '&new_quantity=' + newQuantity);
                    });
                });
            });
        });
    </script>
</body>

</html>
