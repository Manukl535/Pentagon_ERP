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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Delete Inventory Location</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            max-width: 900px;
            margin: 50px auto;
        }

        .scene {
            width: 400px;
            height: 350px;
            perspective: 600px;
            margin: 20px;
            position: relative;
        }

        .card {
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
            transform-style: preserve-3d;
            transition: transform 0.6s;
            border-radius: 8px;
        }

        .card.is-flipped {
            transform: rotateY(180deg);
        }

        .card__face {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            font-size: 20px;
            color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            padding: 20px;
            box-sizing: border-box;
        }

        .card__face--front {
            background: lightblue;
            transform: rotateY(0deg);
            color:black;
        }

        .card__face--back {
            background: white;
            color: black;
            transform: rotateY(180deg);
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

        .form-group {
            margin-bottom: 20px;
            text-align: left;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #666;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
    <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
        <div class="scene" id="addCard">
            <div class="card is-flipped">
                <div class="card__face card__face--front">Add Location</div>
                <div class="card__face card__face--back">
                    <div>
                        <h4 style="text-align: center;">Add Location</h4>
                        <form id="addForm" action="insert_location.php" method="POST">
                            <div class="form-group">
                                <label for="location" style="font-size: 15px;">Location Name:</label>
                                <input type="text" id="location" name="location" class="form-control" style="width: 350px;" required>
                            </div>
                            <div class="form-group">
                                <label for="capacity" style="font-size: 15px;">Capacity:</label>
                                <input type="number" id="capacity" name="capacity" class="form-control" required min="1">
                                <div id="capacityError" class="error-message"></div>
                            </div>
                            <button type="submit" class="btn">Add Location</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="scene" id="deleteCard">
            <div class="card">
                <div class="card__face card__face--front">Delete Location</div>
                <div class="card__face card__face--back">
                    <div>
                        <h4 style="text-align: center;">Delete Location</h4>
                        <form id="deleteForm" action="delete_location.php" method="POST">
                            <div class="form-group">
                                <label for="delete_location" style="font-size: 15px;">Location Name:</label>
                                
                                <select id="location" name="location" class="form-control" style="width: 350px;" required>
    <option value="">Select Location</option>
    <?php
    // Include the database connection
    include('../includes/connection.php');

    $query = "SELECT location FROM inv_location WHERE available_quantity < 0 OR available_quantity IS NULL";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['location']) . '">' . htmlspecialchars($row['location']) . '</option>';
        }
    } else {
        echo '<option value="">No empty locations found</option>';
    }

    // Close database connection
    $conn->close();
    ?>
</select>

                    </div>
                            <div class="form-group">
                                <label for="remarks" style="font-size: 15px;">Remarks:</label>
                                <input type="text" id="remarks" name="remarks" class="form-control" required>
                            </div>
                            <button type="submit" class="btn delete-btn">Delete Location</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const addCard = document.getElementById('addCard');
        const deleteCard = document.getElementById('deleteCard');

        function flipCard(card) {
            card.classList.toggle('is-flipped');
        }

        function validateAddForm(event) {
            const capacityInput = document.getElementById('capacity');
            const capacityError = document.getElementById('capacityError');

            if (capacityInput.value < 1) {
                event.preventDefault();
                capacityError.textContent = 'Capacity must be greater than or equal to 1';
            } else {
                capacityError.textContent = '';
            }
        }

        document.getElementById('addForm').addEventListener('submit', validateAddForm);

        addCard.addEventListener('click', function(event) {
            if (!event.target.closest('input') && !event.target.closest('form')) {
                flipCard(addCard.querySelector('.card'));
                if (deleteCard.querySelector('.card').classList.contains('is-flipped')) {
                    flipCard(deleteCard.querySelector('.card'));
                }
            }
        });

        deleteCard.addEventListener('click', function(event) {
            if (!event.target.closest('input') && !event.target.closest('form')) {
                flipCard(deleteCard.querySelector('.card'));
                if (addCard.querySelector('.card').classList.contains('is-flipped')) {
                    flipCard(addCard.querySelector('.card'));
                }
            }
        });
    </script>
</body>
</html>
