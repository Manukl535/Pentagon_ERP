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
            width: 350px;
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
            background: crimson;
            transform: rotateY(0deg);
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
    </style>
</head>
<body>
    <div class="container">
        <div class="scene" id="addCard">
            <div class="card">
                <div class="card__face card__face--front">Add Location</div>
                <div class="card__face card__face--back">
                    <div>
                        <h2>Add Location</h2>
                        <form action="insert_location.php" method="POST">
                            <div class="form-group">
                                <label for="location">Location Name:</label>
                                <input type="text" id="location" name="location" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="capacity">Capacity:</label>
                                <input type="number" id="capacity" name="capacity" class="form-control" required min="1">
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
                        <h2>Delete Location</h2>
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
            </div>
        </div>
    </div>

    <script>
        const addCard = document.getElementById('addCard');
        const deleteCard = document.getElementById('deleteCard');

        function flipCard(card) {
            card.classList.toggle('is-flipped');
        }

        addCard.addEventListener('click', function(event) {
            if (!event.target.closest('input')) {
                flipCard(addCard.querySelector('.card'));
                if (deleteCard.querySelector('.card').classList.contains('is-flipped')) {
                    flipCard(deleteCard.querySelector('.card'));
                }
            }
        });

        deleteCard.addEventListener('click', function(event) {
            if (!event.target.closest('input')) {
                flipCard(deleteCard.querySelector('.card'));
                if (addCard.querySelector('.card').classList.contains('is-flipped')) {
                    flipCard(addCard.querySelector('.card'));
                }
            }
        });
    </script>
</body>
</html>
