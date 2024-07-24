<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}


// Initialize variables
$po_number = "";
$order_details = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['po_number'])) {
    $po_number = $_POST['po_number'];

    // Fetch order details for the selected PO number where approved is empty or null
    $sql = "SELECT po, vendor, phone, email, gst, date_of_delivery, article_no, color, size, quantity FROM orders WHERE (approved_by IS NULL OR approved_by = '') AND po = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $po_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $order_details[] = $row;
        }
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Dispatch</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            margin-bottom: 5px;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td {
            vertical-align: middle;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 10% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            max-width: 500px; /* Max width */
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            position: relative;
        }

        .close {
            color: #aaa;
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* Adjust form inside modal */
        #approveForm {
            margin-top: 20px;
        }

        #approveForm label {
            display: block;
            margin-bottom: 5px;
        }

        #approveForm input[type=text], #approveForm button {
            padding: 8px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box; /* Ensure padding and border do not increase element width */
            margin-bottom: 10px;
        }

        #approveForm button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
        }

        #approveForm button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    
<h2>Order Dispatch</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="po_number"> Enter or Scan PO Number:</label>
    <select name="po_number" id="po_number">
        <option value="" selected disabled>Select PO Number</option>
        <?php
        // Assuming you have a database connection already established ($conn)
        $sql = "SELECT DISTINCT po FROM orders WHERE approved_by IS NULL OR approved_by = ''";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $po = htmlspecialchars($row['po']);
                echo "<option value='$po' " . ($po_number == $po ? 'selected' : '') . ">$po</option>";
            }
        } else {
            echo "<option value=''>No PO numbers found</option>";
        }
        ?>
    </select>
    <button type="submit">Get Details</button>
</form>

<hr>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($order_details)): ?>
    <h2>Order Details for PO Number: <?php echo htmlspecialchars($po_number); ?></h2>
    <table>
        <tr>
            <th>PO Number</th>
            <th>Vendor</th>
            <th>Phone</th>
            <th>Email</th>
            <th>GST</th>
            <th>Date of Delivery</th>
            <th>Article Number</th>
            <th>Color</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        <?php foreach ($order_details as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['po']); ?></td>
                <td><?php echo htmlspecialchars($row['vendor']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['gst']); ?></td>
                <td><?php echo htmlspecialchars($row['date_of_delivery']); ?></td>
                <td><?php echo htmlspecialchars($row['article_no']); ?></td>
                <td><?php echo htmlspecialchars($row['color']); ?></td>
                <td><?php echo htmlspecialchars($row['size']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><button class="approve-btn" data-po="<?php echo htmlspecialchars($row['po']); ?>" data-article="<?php echo htmlspecialchars($row['article_no']); ?>" data-color="<?php echo htmlspecialchars($row['color']); ?>" data-size="<?php echo htmlspecialchars($row['size']); ?>" data-qty="<?php echo htmlspecialchars($row['quantity']); ?>">Approve</button></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <p>No orders found for PO Number: <?php echo htmlspecialchars($po_number); ?></p>
<?php endif; ?>

<!-- Modal -->
<div id="approveModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Approve Order</h2>
        <form id="approveForm">
            <input type="hidden" id="modal-po" name="modal-po"> 
            <label for="modal-po-display">PO Number:</label>
            <input type="text" id="modal-po-display" name="modal-po-display" readonly> 
            <label for="modal-article">Article Number:</label>
            <input type="text" id="modal-article" name="modal-article" readonly>
            <label for="modal-color">Color:</label>
            <input type="text" id="modal-color" name="modal-color" readonly>
            <label for="modal-size">Size:</label>
            <input type="text" id="modal-size" name="modal-size" readonly>
            <label for="modal-qty">Quantity:</label>
            <input type="text" id="modal-qty" name="modal-qty">
            <label for="modal-approved-by">Approved By:</label>
            <input type="text" id="modal-approved-by" name="modal-approved-by" required>
            <button type="submit" id="modal-approve-btn">Approve</button>
        </form>
    </div>
</div>

<script>
// Modal script
var modal = document.getElementById('approveModal');
var approveBtns = document.getElementsByClassName('approve-btn');
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks the button, open the modal 
for (var i = 0; i < approveBtns.length; i++) {
    approveBtns[i].onclick = function() {
        var po = this.getAttribute('data-po');
        var article = this.getAttribute('data-article');
        var color = this.getAttribute('data-color');
        var size = this.getAttribute('data-size');
        var qty = this.getAttribute('data-qty');

        // Populate modal fields
        document.getElementById('modal-po').value = po;
        document.getElementById('modal-po-display').value = po;
        document.getElementById('modal-article').value = article;
        document.getElementById('modal-color').value = color;
        document.getElementById('modal-size').value = size;
        document.getElementById('modal-qty').value = qty;

        modal.style.display = "block";
    }
}

// When the user submits the approval form
document.getElementById('approveForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var po = document.getElementById('modal-po').value;
    var article = document.getElementById('modal-article').value;
    var color = document.getElementById('modal-color').value;
    var size = document.getElementById('modal-size').value;
    var qty = document.getElementById('modal-qty').value;
    var approvedBy = document.getElementById('modal-approved-by').value;

    // Implement AJAX to update the status in the database
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'approve_order.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Update button text and disable further clicks
            var approveBtn = document.querySelector('button[data-po="' + po + '"]');
            approveBtn.textContent = 'Approved';
            approveBtn.disabled = true;
            // Optionally, display a message or perform additional actions upon success
            alert(xhr.responseText); // Display response from PHP script
        } else {
            // Handle errors if any
            alert('Error: ' + xhr.statusText);
        }
    };
    xhr.send('po=' + encodeURIComponent(po) + '&article_no=' + encodeURIComponent(article) + '&color=' + encodeURIComponent(color) + '&size=' + encodeURIComponent(size) + '&qty=' + encodeURIComponent(qty) + '&approved_by=' + encodeURIComponent(approvedBy));
    modal.style.display = "none";
});
</script>

</body>
</html>
