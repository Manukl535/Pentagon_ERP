<?php
session_start();
include('../Includes/connection.php');

// Query to fetch inventory locations and details
$query = "SELECT * FROM inv_location";
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
        text-align: left;
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
                        <th>Capacity</th>
                        <th>Article No</th>
                        <th>Description</th>
                        <th>Color</th>
                        <th>Available Quantity</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Status</th>
                        <th>Audit Quantity</th>
                        <th>Remaining Quantity</th>
                        <th>Audit Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['location']; ?></td>
                            <td><?php echo $row['capacity']; ?></td>
                            <td><?php echo $row['article_no']; ?></td>
                            <td><?php echo $row['article_description']; ?></td>
                            <td><?php echo $row['color']; ?></td>
                            <td><?php echo $row['available_quantity']; ?></td>
                            <td><?php echo $row['category']; ?></td>
                            <td><?php echo $row['size']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <?php
                                $article_no = $row['article_no'];
                                $location = $row['location'];
                                echo isset($auditQuantities[$article_no][$location]) ? $auditQuantities[$article_no][$location] : 0;
                                ?>
                            </td>
                            <td>
                                <?php
                                $audit_qty = isset($auditQuantities[$article_no][$location]) ? $auditQuantities[$article_no][$location] : 0;
                                echo $row['available_quantity'] - $audit_qty;
                                ?>
                            </td>
                            <td>
                            <?php
                                $audit_qty = isset($auditQuantities[$article_no][$location]) ? $auditQuantities[$article_no][$location] : 0;
                                $remaining_qty = $row['available_quantity'] - $audit_qty;
                                if ($remaining_qty != 0) {
                                    echo '<span style="color: red;">Abnormal</span>';
                                } else {
                                    echo '<span style="color: green;">Normal</span>';
                                }
                            ?>
                            </td>
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
