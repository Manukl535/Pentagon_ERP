<?php
// session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Check if form submitted and process the insertion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // SQL query to insert into audit_log from inv_location
    $sql = "INSERT INTO audit_log (location, article_no, description, color, size, category, qty_23_24)
            SELECT inv.location, inv.article_no, inv.article_description AS description, 
                   inv.color, inv.article_size AS size, inv.category, inv.available_quantity AS qty_23_24
            FROM inv_location inv
            ON DUPLICATE KEY UPDATE audit_log.location = inv.location";  // Assuming 'location' is unique in audit_log

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Audit log updated successfully.');</script>";
    } else {
        // Check if the error is due to duplicate key violation
        if (strpos($conn->error, "Duplicate entry") !== false) {
            echo "<script>alert('Audit log already updated.');</script>";
        } else {
            echo "<p>Error inserting records: " . $conn->error . "</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Audit Log Insertion</title>
<style>
    button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
      <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <button id="auditButton" type="submit" style="display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;">Start Audit</button>
    </form>
</body>
</html>