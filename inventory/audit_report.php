<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Query to fetch inventory locations and details
$query = "SELECT * FROM audit_log";
$result = $conn->query($query);

// Query to fetch audit logs grouped by article number and location
$query1 = "SELECT article_no, location, SUM(audit_quantity) AS total_audit_quantity 
           FROM audit_log 
           GROUP BY article_no, location";
$result1 = $conn->query($query1);

// Associative array to store audit quantities per article number and location
$auditQuantities = array();

// Fetch and store audit quantities grouped by article number and location
while ($audit_row = $result1->fetch_assoc()) {
    $article_no = $audit_row['article_no'];
    $location = $audit_row['location'];
    $auditQuantities[$article_no][$location] = $audit_row['total_audit_quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
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

    h2, h3 {
        color: #333;
        text-align: center;
    }

    form {
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    form div {
        flex: 0 0 48%;
        padding: 10px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
    }

    input {
        width: 90%;
        padding: 8px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    button {
        background-color: #4caf50;
        color: #fff;
        padding: 10px;
        border: none;
        cursor: pointer;
        border-radius: 50px;
    }

    button:hover {
        background-color: #45a049;
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

    a {
        color: #d9534f;
        text-decoration: none;
        cursor: pointer;
    }

    a:hover {
        text-decoration: underline;
    }

    .update-btn {
        background-color: #008CBA;
        color: #fff;
        padding: 5px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
    }

    .update-btn:hover {
        background-color: #00587a;
    }

    .update-row {
        display: none;
    }

    .editable-qty input {
        width: 60px;
        margin-right: 10px;
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

    .normal {
        color: green;
        font-weight: bold;
    }

    .abnormal {
        color: red;
        font-weight: bold;
    }
    </style>
</head>
<body>
    <div class="main-content">
        <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
        <div>
            <h2>Audit Report FY 2024-25</h2>
            <button class="export-btn" onclick="exportToExcel('product-table')">Export to Excel</button>
            <table id="product-table">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Article No</th>
                        <th>Size</th>
                        <th>Qty 23-24</th>
                        <th>Audit Qty</th>
                        <th>Difference</th>
                        <th>Audit Status</th>
                    </tr>
                </thead>
                <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['location']); ?></td>
            <td><?php echo htmlspecialchars($row['article_no']); ?></td>
            <td>
                <?php 
                    // Query to fetch size based on article_no and location
                    $article_no = $row['article_no'];
                    $location = $row['location'];
                    $query_size = "SELECT article_size FROM inv_location WHERE article_no = '$article_no' AND location = '$location'";
                    $result_size = $conn->query($query_size);
                    $row_size = $result_size->fetch_assoc();
                    echo ($row_size) ? htmlspecialchars($row_size['article_size']) : 'N/A';
                ?>
            </td>
            <td><?php echo htmlspecialchars($row['qty_23_24']); ?></td>
            <td><?php echo htmlspecialchars($row['audit_quantity']); ?></td>
            
            <?php 
                // Convert to integers before subtraction
                $audit_quantity = (int)$row['audit_quantity'];
                $qty_23_24 = (int)$row['qty_23_24'];
                
                // Perform subtraction
                $remaining_qty = $audit_quantity - $qty_23_24;
                
                // Determine audit status
                $audit_status = ($remaining_qty == 0) ? 'Normal' : 'Abnormal';
                $status_color = ($remaining_qty == 0) ? 'normal' : 'abnormal'; 
            ?>
            <td><?php echo $remaining_qty; ?></td>
            <td class="<?php echo $status_color; ?>"><?php echo $audit_status; ?></td>
        </tr>
    <?php } ?>
</tbody>

            </table>
        </div>
    </div>

    <script>
        function exportToExcel(tableId) {
            var tab_text = "<table border='2px'><tr>";
            var textRange; var j = 0;
            var tab = document.getElementById(tableId);

            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");
            tab_text = tab_text.replace(/<img[^>]*>/gi, "");
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, "");

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                var txtArea1 = window.open('about:blank', 'excel', '');
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                txtArea1.document.execCommand("SaveAs", true, "Audit_Report.xls");
            } else {
                var sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            }

            return (sa);
        }
    </script>
</body>
</html>
