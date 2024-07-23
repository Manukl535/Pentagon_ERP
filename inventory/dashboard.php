<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Function to check overall Audit Report status
function getAuditReportStatus($conn) {
    // Query to fetch available quantities from audit_log table
    $query = "SELECT SUM(audit_quantity) AS total_audit_quantity, SUM(qty_23_24) AS total_available_quantity  FROM audit_log";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $total_audit_quantity = (int)$row['total_audit_quantity']; // Cast to integer for safe comparison
    $total_available_quantity = (int)$row['total_available_quantity']; // Cast to integer for safe comparison

    // Calculate the difference
    $difference = $total_audit_quantity - $total_available_quantity;

    // Determine status dynamically
    if ($difference == 0) {
        return "Normal";
    } else {
        return ($difference > 0) ? "+".$difference : $difference; // Return the difference as abnormal
    }
}

// Get overall Audit Report status
$auditReportStatus = getAuditReportStatus($conn);

    // Total stocks in inv

    $query = "SELECT SUM(available_quantity) AS total_stocks FROM inv_location";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $total_stocks = $row['total_stocks'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Inv Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        html,
        body,
        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: "Raleway", sans-serif;
        }
    </style>
</head>

<body class="w3-light-grey">

    <div class="w3-bar w3-top w3-black w3-large" style="z-index:4">
        <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i> &nbsp;Menu</button>
        <span class="w3-bar-item w3-left"><a href="index.php" style="text-decoration:none;">Home</a></span>
        <span class="w3-bar-item w3-right"><a href="../logout.php" style="text-decoration:none;">Logout</a></span>
    </div>

    <nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;" id="mySidebar"><br>
        <div class="w3-container w3-row">
            <div class="w3-col s8 w3-bar">
                <span style="padding-top:0">Welcome <?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>,</span><br>
                <span id="greeting"></span><br>
                <span id="real-time"></span>
            </div>
        </div>
        <hr>
        <div class="w3-container" style="padding-top:0">
        </div>
        <div class="w3-bar-block">
            <a href="#" class="w3-bar-item w3-button w3-padding w3-blue"><i class="material-icons" style="font-size:15px">dashboard</i>&nbsp; Overview</a>
            <div style="margin-top: 10px;"></div>
            <a href="stocks.php" class="w3-bar-item w3-button w3-padding w3-brown"><i class="fa fa-cubes" style="font-size:15px"></i>&nbsp; Stocks(<?php echo $total_stocks ?>)</a>
            <div style="margin-top: 10px;"></div>
            <a href="#" class="w3-bar-item w3-button w3-padding w3-green"><i class='fa fa-line-chart' style='font-size:15px'></i>&nbsp; Sales</a>
            <div style="margin-top: 10px;"></div>
            <a href="audit_report.php" class="w3-bar-item w3-button w3-padding w3-red"><i class="fa fa-list" style="font-size:15px"></i>&nbsp; Audit Report (<?php echo $auditReportStatus; ?>)</a>
            <div style="margin-top: 10px;"></div>
            <a href="safety_report_details.php" class="w3-bar-item w3-button w3-padding w3-yellow"><i class="fa fa-heartbeat" style="font-size:24px"></i>&nbsp; Safety Report</a>
        </div>
    </nav>

    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

    <div class="w3-main" style="margin-left:300px;margin-top:43px;">

        <header class="w3-container" style="padding-top:0">
            <h5><b><i class="fa fa-dashboard"></i> Dashboard</b></h5>
        </header>

        <div class="w3-row-padding w3-margin-bottom">
            <div class="w3-quarter">
                <div class="w3-container w3-brown w3-padding-16">
                    <div class="w3-left"><i style="font-size:58px" class="fa">&#xf1b3;</i></div>
                    <div class="w3-right">
                        <h3></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Stocks (<?php echo $total_stocks ?>)</h4>

                </div>
            </div>
            <div class="w3-quarter">
                <div class="w3-container w3-green w3-padding-16">
                    <div class="w3-left"><i style="font-size:58px" class="fa">&#xf201;</i></div>
                    <div class="w3-right">
                        <h3></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Sales</h4>
                </div>
            </div>
            <div class="w3-quarter">
                <div class="w3-container w3-red w3-padding-16">
                    <div class="w3-left"><i style="font-size:58px" class="fa">&#xf03a;</i></div>
                    <div class="w3-right">
                        <h3></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Audit Report (<?php echo $auditReportStatus; ?>)</h4>
                </div>
            </div>
            <div class="w3-quarter">
                <div class="w3-container w3-yellow w3-padding-16">
                    <div class="w3-left"><i style="font-size:58px" class="fa">&#xf21e;</i></div>
                    <div class="w3-right">
                        <h3></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Safety Report</h4>
                </div>
            </div>
        </div>
        <div class="w3-container">
            <h4>General Stats</h4>

            <div class="w3-row">
                <div class="w3-half">
                    <div class="w3-container">
                        <h5>Weekly Stock Movement</h5>
                        <canvas id="stockChart" style="max-width: 100%;"></canvas>
                    </div>
                </div>
                <div class="w3-half">
                    <div class="w3-container">
                        <h5>Top Products</h5>
                        <canvas id="topProductsChart" style="max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <script>
                // Dashboard graphs using Chart.js

// Sample data for weekly stock movement
const weeklyStockData = {
    labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
    datasets: [{
        label: "Stock Movement",
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
        data: [50, 60, 70, 65, 55], // Sample data for stock movement
    }]
};

// Function to render the stock chart
function renderStockChart() {
    const ctx = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(ctx, {
        type: 'line',
        data: weeklyStockData,
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

// Sample data for top products
const topProductsData = {
    labels: ["APT101", "APK104", "FSA107", "FSA107", "AWC121"],
    datasets: [{
        label: "Top Products",
        backgroundColor: "rgba(54, 162, 235, 0.2)",
        borderColor: "rgba(54, 162, 235, 1)",
        borderWidth: 1,
        data: [100, 80, 120, 90, 110], // Sample data for top products
    }]
};

// Function to render the top products chart
function renderTopProductsChart() {
    const ctx = document.getElementById('topProductsChart').getContext('2d');
    const topProductsChart = new Chart(ctx, {
        type: 'bar',
        data: topProductsData,
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

// Functions for sidebar navigation
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}

// Function for toggling mail display
function openMail(personName) {
    var i;
    var x = document.getElementsByClassName("person");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    x = document.getElementsByClassName("test");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" w3-light-grey", "");
    }
    document.getElementById(personName).style.display = "block";
    event.currentTarget.className += " w3-light-grey";
}

// Render the charts when the page loads
renderStockChart();
renderTopProductsChart();

            </script>
        </div>

        <center>
            <br />
            <p>2024 &#169; All Rights Reserved | Developed and Maintained by <b>Pentagon</b></p>

        </center>


        <script>
            //Real time formats
            function updateTimeAndGreeting() {
                // Get current time
                var now = new Date();
                var hours = now.getHours();
                var minutes = now.getMinutes();
                var seconds = now.getSeconds();

                // Format hours, minutes, and seconds to have leading zeros if needed
                hours = (hours < 10 ? "0" : "") + hours;
                minutes = (minutes < 10 ? "0" : "") + minutes;
                seconds = (seconds < 10 ? "0" : "") + seconds;

                // Display the time in the format "10:10:00"
                document.getElementById("real-time").textContent = "Time: " + hours + ":" + minutes + ":" + seconds;

                // Determine the greeting based on the current hour
                var greeting;
                if (hours < 12) {
                    greeting = "Good Morning!";
                } else if (hours >= 12 && hours < 18) {
                    greeting = "Good Afternoon!";
                } else {
                    greeting = "Good Evening!";
                }

                // Display the greeting
                document.getElementById("greeting").textContent = greeting;
            }

            // Call updateTimeAndGreeting function every second to update the clock and greeting
            setInterval(updateTimeAndGreeting, 1000);

            // Initial call to display time and greeting immediately
            updateTimeAndGreeting();

        </script>

</body>

</html>
