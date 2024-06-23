<?php
session_start();
include('../Includes/connection.php');

// Check if form is submitted and article_no is set for editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['article_no'])) {
    $articleNo = mysqli_real_escape_string($conn, $_POST['article_no']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    $color = mysqli_real_escape_string($conn, $_POST['color']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update query
    $query = "UPDATE inv_location SET 
                article_description = '$description', 
                available_quantity = '$quantity', 
                location = '$location', 
                capacity = '$capacity',
                color = '$color',
                size = '$size',
                category = '$category',
                status = '$status' 
              WHERE article_no = '$articleNo'";

    if (mysqli_query($conn, $query)) {
        // Redirect back to the main page after successful update
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

// Check if form is submitted for adding new item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_description'])) {
    $newDescription = mysqli_real_escape_string($conn, $_POST['new_description']);
    $newQuantity = mysqli_real_escape_string($conn, $_POST['new_quantity']);
    $newLocation = mysqli_real_escape_string($conn, $_POST['new_location']);
    $newCapacity = mysqli_real_escape_string($conn, $_POST['new_capacity']);
    $newColor = mysqli_real_escape_string($conn, $_POST['new_color']);
    $newSize = mysqli_real_escape_string($conn, $_POST['new_size']);
    $newCategory = mysqli_real_escape_string($conn, $_POST['new_category']);
    $newStatus = mysqli_real_escape_string($conn, $_POST['new_status']);

    // Insert query
    $query = "INSERT INTO inv_location (article_description, available_quantity, location, capacity, color, size, category, status) 
              VALUES ('$newDescription', '$newQuantity', '$newLocation', '$newCapacity', '$newColor', '$newSize', '$newCategory', '$newStatus')";

    if (mysqli_query($conn, $query)) {
        // Redirect back to the main page after successful insert
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory Management System</title>
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
        background-color: #008CBA;
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

    /* Modal styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto; /* 10% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
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

    /* Form styles */
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

    .form-group select {
        width: calc(100% - 12px);
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
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
                <th>BinStatus</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query to fetch inventory items
            $query = "SELECT article_no, article_description, available_quantity, location, capacity, color, size, category, status FROM inv_location";
            $result = $conn->query($query);

            // Check if query executed successfully
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
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>";
                    echo "<button class='action-btn edit-btn' 
                                 data-article-no='" . htmlspecialchars($row['article_no']) . "' 
                                 data-description='" . htmlspecialchars($row['article_description']) . "' 
                                 data-quantity='" . htmlspecialchars($row['available_quantity']) . "' 
                                 data-location='" . htmlspecialchars($row['location']) . "' 
                                 data-capacity='" . htmlspecialchars($row['capacity']) . "' 
                                 data-color='" . htmlspecialchars($row['color']) . "' 
                                 data-size='" . htmlspecialchars($row['size']) . "' 
                                 data-category='" . htmlspecialchars($row['category']) . "' 
                                 data-status='" . htmlspecialchars($row['status']) . "'>Edit</button>";
                    echo "<button class='action-btn delete-btn'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No items found</td></tr>";
            }
            ?>
        </tbody>
    </table>

</div>

<!-- Modal for editing -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Item <span id="editArticleNoDisplay"></span></h2>
        <form id="editForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" id="editArticleNo" name="article_no">
            <div class="form-group">
                <label for="editDescription">Description:</label>
                <input type="text" id="editDescription" name="description">
            </div>
            <div class="form-group">
                <label for="editQuantity">Quantity:</label>
                <input type="number" id="editQuantity" name="quantity">
            </div>
            <div class="form-group">
                <label for="editLocation">Location:</label>
                <input type="text" id="editLocation" name="location">
            </div>
            <div class="form-group">
                <label for="editCapacity">Capacity:</label>
                <input type="text" id="editCapacity" name="capacity">
            </div>
            <div class="form-group">
                <label for="editColor">Color:</label>
                <input type="text" id="editColor" name="color">
            </div>
            <div class="form-group">
                <label for="editSize">Size:</label>
                <input type="text" id="editSize" name="size">
            </div>
            <div class="form-group">
                <label for="editCategory">Category:</label>
                <input type="text" id="editCategory" name="category">
            </div>
            <div class="form-group">
                <label for="editStatus">Bin Status:</label>
                <select id="editStatus" name="status">
                    <option value="Available">Available</option>
                    <option value="Not Available">Not Available</option>
                </select>
            </div>
            <button type="submit">Save Changes</button>
        </form>
    </div>
</div>

<!-- Form for adding new item -->
<div class="form-container" id="addFormContainer" style="display: none;">
    <h2>Add New Item</h2>
    <form id="addForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label for="new_description">Description:</label>
            <input type="text" id="new_description" name="new_description">
        </div>
        <div class="form-group">
            <label for="new_quantity">Quantity:</label>
            <input type="number" id="new_quantity" name="new_quantity">
        </div>
        <div class="form-group">
            <label for="new_location">Location:</label>
            <input type="text" id="new_location" name="new_location">
        </div>
        <div class="form-group">
            <label for="new_capacity">Capacity:</label>
            <input type="text" id="new_capacity" name="new_capacity">
        </div>
        <div class="form-group">
            <label for="new_color">Color:</label>
            <input type="text" id="new_color" name="new_color">
        </div>
        <div class="form-group">
            <label for="new_size">Size:</label>
            <input type="text" id="new_size" name="new_size">
        </div>
        <div class="form-group">
            <label for="new_category">Category:</label>
            <input type="text" id="new_category" name="new_category">
        </div>
        <div class="form-group">
            <label for="new_status">Bin Status:</label>
            <select id="new_status" name="new_status">
                <option value="Available">Available</option>
                <option value="Not Available">Not Available</option>
            </select>
        </div>
        <button type="submit">Add Item</button>
    </form>
</div>

<script>
    // JavaScript to handle edit button click and modal functionality
    const editButtons = document.querySelectorAll('.edit-btn');
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const editArticleNo = document.getElementById('editArticleNo');
    const editDescription = document.getElementById('editDescription');
    const editQuantity = document.getElementById('editQuantity');
    const editLocation = document.getElementById('editLocation');
    const editCapacity = document.getElementById('editCapacity');
    const editColor = document.getElementById('editColor');
    const editSize = document.getElementById('editSize');
    const editCategory = document.getElementById('editCategory');
    const editStatus = document.getElementById('editStatus');
    const editArticleNoDisplay = document.getElementById('editArticleNoDisplay');
    const closeBtn = document.querySelector('.close');
    const addBtn = document.querySelector('.add-button');
    const addFormContainer = document.getElementById('addFormContainer');

    // Edit button click event
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Fetch data from the button's data attributes
            let articleNo = this.getAttribute('data-article-no');
            let description = this.getAttribute('data-description');
            let quantity = this.getAttribute('data-quantity');
            let location = this.getAttribute('data-location');
            let capacity = this.getAttribute('data-capacity');
            let color = this.getAttribute('data-color');
            let size = this.getAttribute('data-size');
            let category = this.getAttribute('data-category');
            let status = this.getAttribute('data-status');

            // Populate form fields with fetched data
            editArticleNo.value = articleNo;
            editDescription.value = description;
            editQuantity.value = quantity;
            editLocation.value = location;
            editCapacity.value = capacity;
            editColor.value = color;
            editSize.value = size;
            editCategory.value = category;
            editStatus.value = status;
            editArticleNoDisplay.textContent = `#${articleNo}`;

            // Display the edit modal
            editModal.style.display = 'block';
        });
    });

    // Close the edit modal if close button or outside modal area is clicked
    closeBtn.addEventListener('click', function() {
        editModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == editModal) {
            editModal.style.display = 'none';
        }
    });

    // Show add form on add button click
    addBtn.addEventListener('click', function() {
        addFormContainer.style.display = 'block';
    });

</script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
