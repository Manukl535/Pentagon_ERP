<?php
session_start();
// Include the database connection
include('../includes/connection.php');

if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Delete Inventory Location</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            max-width: 900px;
            margin: 50px auto;
        }

        .box {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 45%;
        }

        .left-box, .right-box {
            margin-bottom: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #666;
        }

        input[type="text"], input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn, .delete-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            display: block;
            margin: 0 auto;
            transition: background-color 0.3s ease;
        }

        .btn:hover, .delete-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box left-box">
            <h2>Add Inventory Location</h2>
            <form action="insert_location.php" method="POST">
                <div class="form-group">
                    <label for="location">Location Name:</label>
                    <input type="text" id="location" name="location" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="capacity">Capacity:</label>
                    <input type="number" id="capacity" name="capacity" class="form-control" required min="0">
                    <!-- min="0" ensures the input is a non-negative integer -->
                </div>
                
                <button type="submit" class="btn">Add Location</button>
            </form>
        </div>
        <div class="box right-box">
            <h2>Delete Inventory Location</h2>
            <form action="delete_location.php" method="POST">
                <div class="form-group">
                    <label for="delete_location">Location Name:</label>
                    <input type="text" id="delete_location" name="delete_location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks:</label>
                    <input type="text" id="remarks" name="remarks" class="form-control" required>
                </div>
                <button type="submit" class="btn delete-btn">Delete Location</button>
            </form>
        </div>
    </div>
</body>
</html>
