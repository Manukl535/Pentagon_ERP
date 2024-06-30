<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection or any other necessary includes
include("../includes/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associate Dashboard</title>
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(1, 1fr); 
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .module {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px; 
        }
        .module a {
            text-decoration: none;
            color: inherit;
        }
        .module-icon {
            font-size: 36px;
            margin-right: 20px;
            width: 36px; 
            height: 36px; 
        }
        .module-name {
            font-size: 18px;
            font-weight: bold;
        }
        /* Media Query for Tablets and Above */
        @media (min-width: 768px) {
            .dashboard {
                grid-template-columns: repeat(2, 1fr); /* Two columns layout for tablets and larger */
            }
        }
        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF; 
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px; 
            border: none; 
            font-size: 16px;
            cursor: pointer; 
        }
        .logout-button:hover {
            background-color: #0056b3; 
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            text-align: center;
        }
        .header .welcome-message {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="welcome-message">Welcome, <?php echo ucfirst($_SESSION['username']); ?></div>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
    
    <div class="dashboard">
        <!-- Receiving Module -->
        <div class="module">
            <div>
                <!-- Assuming hands.png is your receiving icon -->
                <span class="module-icon"><img src="hands.png" alt="Receiving Icon" style="width: 36px; height: 36px;"></span>
                <a href="receipt.php" class="module-link"><span class="module-name">Receipt</span></a>
            </div>
            
        </div>

        <!-- Picking Module -->
        <div class="module">
            <div>
                <span class="module-icon"><i style="font-size: 24px;" class="fas fa-shopping-cart"></i></span>
                <a href="picking.php" class="module-link"><span class="module-name">Picking</span></a>
            </div>
            
        </div>

        <!-- Inventory Module -->
        <div class="module">
            <div>
                <span class="module-icon"><i style="font-size: 24px;" class="fas fa-cubes"></i></span>
                <a href="inventory.php" class="module-link"><span class="module-name">Inventory</span> </a>
            </div>
           
        </div>

        <!-- Settings Module -->
        <div class="module">
            <div>
                <span class="module-icon"><i style="font-size:24px" class="fas fa-cogs"></i></span>
                <a href="settings.php" class="module-link"><span class="module-name">Settings</span></a>
            </div>
            
        </div>
    </div>
</body>
</html>
