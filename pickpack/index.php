<?php
session_start();
include('../includes/connection.php');

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickpack</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="styles0.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="script.js"></script>
</head>
<body>
    
    <section>
        <div class="item">
            <a href="mail.php" style="text-decoration:none">
              <i class="fas fa-envelope" style="font-size: 100px; color: black;"></i>
            </a>

            <div>Mails</div>
        </div>
        <div class="item">
            <a href="dashboard.php" style="text-decoration:none"> 
                <i class="fas fa-tachometer-alt" style="font-size: 100px; color: black;"></i>
            </a>
            <div>Dashboard</div>
        </div>
        <div class="item">
            <i class="fas fa-tools" style="font-size: 100px; color: black;"></i>
            <div class="dropdown">
                <div>Works <i class="fa" style="font-size: 15px; ">&#11167;</i></div>
                <div class="dropdown-content">
                    <a href="assign.php">Assign Orders</a>
                    <a href="transfer_order.php">Transfer Order</a>
                    <a href="safety_report.php">Report Issues</a>
                   
                </div>
            </div>
        </div>

        <div class="item">
            <a href="settings.php"><i class="fa fa-gear fa-spin" style="font-size:100px;color: black;"></i></a>
            <div>Settings</div>
        </div>
    </section>

    <nav class="navbar">
        <div class="nav-item">
            <i style='font-size:24px' class='fas'>&#xf086;</i>
        </div>
        <div class="nav-item">
            <span id="time"></span>
        </div>
        <div class="nav-item">
            <a href="../logout.php">Logout</a>
        </div>
    </nav>



</body>
</html>
