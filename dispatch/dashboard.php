<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}
// Retrieve the logged-in user's email from session
$user_email = $_SESSION['email'];
function getTotalVendors()
{
    global $conn;
    $sql = "SELECT COUNT(*) AS total_vendors FROM vendors";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total_vendors'];
}

function getTotalOrders(){
    global $conn;
    $sql = "SELECT COUNT(*) AS total_orders FROM orders";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total_orders'];
}

function getReceivedOrders(){
    global $conn;
    $sql = "SELECT COUNT(*) AS po_number FROM approved_po WHERE approved_by IS NOT NULL";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['po_number'];
}

function getTopSellingProducts() {
    global $conn;
    $sql = "SELECT article_no AS product_name, SUM(quantity) AS total_ordered FROM orders GROUP BY product_name ORDER BY total_ordered DESC LIMIT 5";
    $result = mysqli_query($conn, $sql);
    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = [
            'product_name' => $row['product_name'],
            'total_ordered' => $row['total_ordered']
        ];
    }
    return $products;
}

// Fetch data for Ordered goods and Received Goods
$orderedGoods = getTotalOrders();
$receivedGoods = getReceivedOrders();

// Fetch top selling products data
$topSellingProducts = getTopSellingProducts();
$productNames = array_column($topSellingProducts, 'product_name');
$totalOrdered = array_column($topSellingProducts, 'total_ordered');

function toGetReportedIssues() {
    global $conn;
    $sql = "SELECT COUNT(*) AS total_issues FROM safety_reports";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total_issues'];    

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dispatch Dashboard</title>
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
                <span style="padding-top:0">Welcome <?php echo isset($_SESSION['name']) ? ucfirst($_SESSION['name']) : ''; ?>,</span><br>
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
            <a href="vendors.php" class="w3-bar-item w3-button w3-padding w3-brown"><i style="font-size:15px" class="fa"> &#xf0c0;</i>&nbsp;Customers(<?php echo getTotalVendors(); ?>)</a>
            <div style="margin-top: 10px;"></div>
            <a href="goods_ordered.php" class="w3-bar-item w3-button w3-padding w3-red"><i style="font-size:15px" class="fa">&#xf0ae;</i>&nbsp; Ordered PO(<?php echo $orderedGoods; ?>)</a>
            <div style="margin-top: 10px;"></div>
            <a href="goods_received.php" class="w3-bar-item w3-button w3-padding w3-green"><span style='font-size:15px;'>&#10004;</span>&nbsp; Dispatched Goods(<?php echo $receivedGoods; ?>)</a>
                <div style="margin-top: 10px;"></div>
            <a href="safety_report_details.php" class="w3-bar-item w3-button w3-padding w3-yellow"><i class="fa fa-heartbeat" style="font-size:24px"></i>&nbsp; Safety Report(<?php echo toGetReportedIssues(); ?>)</a>
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
                    <div class="w3-left"><i style="font-size:58px" class="fa"> &#xf0c0;</i></div>
                    <div class="w3-right">
                        <h3><?php echo getTotalVendors(); ?></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Customers</h4>
                </div>
            </div>

            <div class="w3-quarter">
                <div class="w3-container w3-red w3-padding-16">
                    <div class="w3-left"><i style="font-size:58px" class="fa">&#xf0ae;</i></div>
                    <div class="w3-right">
                        <h3><?php echo $orderedGoods; ?></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Ordered PO</h4>
                </div>
            </div>

            <div class="w3-quarter">
                <div class="w3-container w3-green w3-padding-16">
                    <div class="w3-left"><span style='font-size:40px;'>&#10004;</span></div>
                    <div class="w3-right">
                        <h3><?php echo $receivedGoods; ?></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Dispatched Goods</h4>
                </div>
            </div>
            
            <div class="w3-quarter">
                <div class="w3-container w3-yellow w3-padding-16">
                    <div class="w3-left"><i style="font-size:58px" class="fa">&#xf21e;</i></div>
                    <div class="w3-right">
                    <h3><?php echo toGetReportedIssues(); ?></h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4>Safety Report</h4>
                </div>
            </div>
        </div>

        <div class="w3-row-padding w3-margin-bottom">
            <div class="w3-half">
                <div class="w3-container w3-card w3-white w3-margin-bottom">
                    <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-bar-chart fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Dipatched Orders</h2>
                    <div class="w3-container">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="w3-half">
                <div class="w3-container w3-card w3-white w3-margin-bottom">
                    <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-bar-chart fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Top Vs Least Ordered</h2>
                    <div class="w3-container">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
            
            <center>
    <div style="padding: 10px auto">
        <p>2024 © All Rights Reserved | Developed and Maintained by <b>Pentagon</b></p>
    </div>
</center>

        </div>

        <script>
            // Chart.js code for Stock Ordered vs Received
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('stockChart').getContext('2d');

                var data = {
                    labels: ['Ordered Goods', 'Received Goods'],
                    datasets: [{
                        label: 'Comparison',
                        data: [<?php echo $orderedGoods; ?>, <?php echo $receivedGoods; ?>],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)', // Red color with opacity
                            'rgba(54, 162, 235, 0.2)' // Blue color with opacity
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)', // Red border color
                            'rgba(54, 162, 235, 1)' // Blue border color
                        ],
                        borderWidth: 1
                    }]
                };

                var options = {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                };

                var stockChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: options
                });
            });

            // Chart.js code for Top ordered Products
            document.addEventListener("DOMContentLoaded", function() {
                var ctx = document.getElementById('topProductsChart').getContext('2d');

                var data = {
                    labels: <?php echo json_encode($productNames); ?>,
                    datasets: [{
                        label: 'Total Ordered',
                        data: <?php echo json_encode($totalOrdered); ?>,
                        backgroundColor: 'rgba(255, 159, 64, 0.2)', // Orange color with opacity
                        borderColor: 'rgba(255, 159, 64, 1)', // Orange border color
                        borderWidth: 1
                    }]
                };

                var options = {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                };

                var topProductsChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: options
                });
            });

            // Real time formats
            function updateTimeAndGreeting() {
                var now = new Date();
                var hours = now.getHours();
                var minutes = now.getMinutes();
                var seconds = now.getSeconds();

                hours = (hours < 10 ? "0" : "") + hours;
                minutes = (minutes < 10 ? "0" : "") + minutes;
                seconds = (seconds < 10 ? "0" : "") + seconds;

                document.getElementById("real-time").textContent = "Time: " + hours + ":" + minutes + ":" + seconds;

                var greeting;
                if (hours < 12) {
                    greeting = "Good Morning!";
                } else if (hours >= 12 && hours < 18) {
                    greeting = "Good Afternoon!";
                } else {
                    greeting = "Good Evening!";
                }

                document.getElementById("greeting").textContent = greeting;
            }

            setInterval(updateTimeAndGreeting, 1000);
            updateTimeAndGreeting();
        </script>

    </div>
    
</body>

</html>
