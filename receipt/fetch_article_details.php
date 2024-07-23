<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

// Get article number from GET request (assuming it's a GET request based on JavaScript usage)
$articleNo = $_GET['article_no'];

// Query to fetch sizes for the selected article number
$querySizes = "SELECT DISTINCT article_size AS size FROM inv_location WHERE article_no = ?";
$stmtSizes = $conn->prepare($querySizes);
$stmtSizes->bind_param('s', $articleNo);
$stmtSizes->execute();
$resultSizes = $stmtSizes->get_result();

$sizes = [];
while ($row = $resultSizes->fetch_assoc()) {
    $sizes[] = $row['size'];
}

// Query to fetch colors for the selected article number
$queryColors = "SELECT DISTINCT color FROM inv_location WHERE article_no = ?";
$stmtColors = $conn->prepare($queryColors);
$stmtColors->bind_param('s', $articleNo);
$stmtColors->execute();
$resultColors = $stmtColors->get_result();

$colors = [];
while ($row = $resultColors->fetch_assoc()) {
    $colors[] = $row['color'];
}

// Close the database connections
$stmtSizes->close();
$stmtColors->close();
$conn->close();

// Return sizes and colors as JSON
$response = array(
    'sizes' => $sizes,
    'colors' => $colors
);
echo json_encode($response);
?>
