<?php
session_start();
include('../Includes/connection.php');

// Handle form submissions for editing existing items
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['article_no'])) {
    // Retrieve and sanitize input data
    $articleNo = mysqli_real_escape_string($conn, $_POST['article_no']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // Update query with location condition
    $query = "UPDATE inv_location SET 
                article_description = '$description', 
                available_quantity = '$quantity'
              WHERE article_no = '$articleNo' AND location = '$location'";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Redirect back to the main page after successful update
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Handle form submissions for adding new items
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_description'])) {
    // Retrieve and sanitize input data
    $newDescription = mysqli_real_escape_string($conn, $_POST['new_description']);
    $newQuantity = mysqli_real_escape_string($conn, $_POST['new_quantity']);
    $newLocation = mysqli_real_escape_string($conn, $_POST['new_location']);
    $newCapacity = mysqli_real_escape_string($conn, $_POST['new_capacity']);
    $newColor = mysqli_real_escape_string($conn, $_POST['new_color']);
    $newSize = mysqli_real_escape_string($conn, $_POST['new_size']);
    $newCategory = mysqli_real_escape_string($conn, $_POST['new_category']);

    // Insert query
    $query = "INSERT INTO inv_location (article_description, available_quantity, location, capacity, color, size, category) 
              VALUES ('$newDescription', '$newQuantity', '$newLocation', '$newCapacity', '$newColor', '$newSize', '$newCategory')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Redirect back to the main page after successful insert
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
}

// Query to fetch inventory items
$query = "SELECT article_no, article_description, available_quantity, location, capacity, color, size, category FROM inv_location";
$result = $conn->query($query);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory Management System</title>
<script src="script.js"></script>
<style>
/* CSS styles */
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

h1 {
    color: #333;
    text-align: center;
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

.add-button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-size: 16px;
    display: inline-block;
    margin-bottom: 10px;
}

.action-btn {
    padding: 5px 8px;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 3px;
    text-decoration: none;
    display: inline-block;
    margin-right: 5px;
}

.edit-btn {
    background-color: #008CBA;
}

.delete-btn {
    background-color: #f44336;
}

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
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    border-radius: 5px;
    position: relative;
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

.form-container {
    margin-top: 20px;
    background-color: #f2f2f2;
    padding: 20px;
    border-radius: 5px;
}

.form-container h2 {
    text-align: center;
    margin-bottom: 10px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input {
    width: calc(100% - 12px);
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

.form-group input[type="number"] {
    width: 100%;
}

.form-group button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 3px;
}

.form-group button:hover {
    background-color: #45a049;
}
</style>
</head>
<body>

<div class="main-content">
    <h1>Inventory Management</h1>

    <!-- Button to add new item -->
    <a href="#" class="add-button">Add New Item</a>

    <!-- Table to display inventory items -->
    <table>
        <thead>
            <tr>
                <th>Location</th>
                <th>Capacity</th>
                <th>Article Number</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Color</th>
                <th>Size</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are items to display
            if ($result && $result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['article_no']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['article_description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['available_quantity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['color']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['size']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                    echo '<td>
                            <a href="#" class="action-btn edit-btn">Edit</a>
                            <a href="#" class="action-btn delete-btn">Delete</a>
                          </td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No items found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal for editing items -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="form-container">
            <h2>Edit Item</h2>
            <form id="editForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" id="editArticleNo" name="article_no">
                <div class="form-group">
                    <label for="editDescription">Description:</label>
                    <input type="text" id="editDescription" name="description" required>
                </div>
                <div class="form-group">
                    <label for="editQuantity">Quantity:</label>
                    <input type="number" id="editQuantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="editLocation">Location:</label>
                    <input type="text" id="editLocation" name="location" required>
                </div>
                <div class="form-group">
                    <label for="editCapacity">Capacity:</label>
                    <input type="text" id="editCapacity" name="capacity" required>
                </div>
                <div class="form-group">
                    <label for="editColor">Color:</label>
                    <input type="text" id="editColor" name="color" required>
                </div>
                <div class="form-group">
                    <label for="editSize">Size:</label>
                    <input type="text" id="editSize" name="size" required>
                </div>
                <div class="form-group">
                    <label for="editCategory">Category:</label>
                    <input type="text" id="editCategory" name="category" required>
                </div>
                <div class="form-group">
                    <button type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for modal and form functionality -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var editModal = document.getElementById('editModal');
    var editForm = document.getElementById('editForm');
    var closeButtons = document.getElementsByClassName('close');
    var editButtons = document.getElementsByClassName('edit-btn');
    var addBtn = document.querySelector('.add-button');

    // Function to open edit modal and populate form fields
    Array.from(editButtons).forEach(function(button) {
        button.addEventListener('click', function() {
            editModal.style.display = 'block';
            var row = button.parentElement.parentElement;
            document.getElementById('editArticleNo').value = row.cells[2].innerText;
            document.getElementById('editDescription').value = row.cells[3].innerText;
            document.getElementById('editQuantity').value = row.cells[4].innerText;
            document.getElementById('editLocation').value = row.cells[0].innerText;
            document.getElementById('editCapacity').value = row.cells[1].innerText;
            document.getElementById('editColor').value = row.cells[5].innerText;
            document.getElementById('editSize').value = row.cells[6].innerText;
            document.getElementById('editCategory').value = row.cells[7].innerText;
        });
    });

    // Function to close modals
    Array.from(closeButtons).forEach(function(button) {
        button.addEventListener('click', function() {
            editModal.style.display = 'none';
        });
    });

    // Function to open add modal
    addBtn.addEventListener('click', function() {
        addModal.style.display = 'block';
    });

    // Submit edit form function (if needed)
    editForm.addEventListener('submit', function(event) {
        // Additional handling if required
    });

    // Add more JavaScript as needed
});

// cap vs qty

document.addEventListener("DOMContentLoaded", function() {
    var editModal = document.getElementById('editModal');
    var editForm = document.getElementById('editForm');
    var closeButtons = document.getElementsByClassName('close');
    var editButtons = document.getElementsByClassName('edit-btn');
    var addBtn = document.querySelector('.add-button');

    // Function to open edit modal and populate form fields
    Array.from(editButtons).forEach(function(button) {
        button.addEventListener('click', function() {
            editModal.style.display = 'block';
            var row = button.parentElement.parentElement;
            document.getElementById('editArticleNo').value = row.cells[2].innerText;
            document.getElementById('editDescription').value = row.cells[3].innerText;
            document.getElementById('editQuantity').value = row.cells[4].innerText;
            document.getElementById('editLocation').value = row.cells[0].innerText;
            document.getElementById('editCapacity').value = row.cells[1].innerText;
            document.getElementById('editColor').value = row.cells[5].innerText;
            document.getElementById('editSize').value = row.cells[6].innerText;
            document.getElementById('editCategory').value = row.cells[7].innerText;
        });
    });

    // Function to close modals
    Array.from(closeButtons).forEach(function(button) {
        button.addEventListener('click', function() {
            editModal.style.display = 'none';
        });
    });

    // Function to open add modal
    addBtn.addEventListener('click', function() {
        addModal.style.display = 'block';
    });

    // Submit edit form function with validation
    editForm.addEventListener('submit', function(event) {
        var quantity = parseInt(document.getElementById('editQuantity').value);
        var capacity = parseInt(document.getElementById('editCapacity').value);
        
        // Check if quantity exceeds capacity
        if (quantity > capacity) {
            alert("Quantity cannot be greater than Bin Capacity.");
            event.preventDefault(); // Prevent form submission
        }
    });

    // Add more JavaScript as needed
});

</script>

</body>
</html>
