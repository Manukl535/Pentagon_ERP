<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}


// Check if location is set and not empty
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['location'])) {
    $location = $_POST['location'];

    // Prepare SQL query to fetch details from inv_location based on location
    $query = "SELECT article_no, article_description, color, available_quantity, article_size, category FROM inv_location WHERE location = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $location);
    $stmt->execute();
    $stmt->store_result();

    // Bind results to variables
    $stmt->bind_result($article_no, $article_description, $color, $available_quantity, $size, $category);

    // Fetch the row
    if ($stmt->fetch()) {
        // Return JSON response
        $response = array(
            'article_number' => $article_no,
            'quantity' => $available_quantity,
            'description' => $article_description,
            'color' => $color,
            'article_size' => $size, // Ensure article_size is correctly fetched
            'category' => $category
        );
        echo json_encode($response);
    } else {
        // No results found for the location
        echo json_encode(array('error' => 'No data found for the selected location'));
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle if location parameter is missing or empty
    echo json_encode(array('error' => 'Location parameter is missing or empty'));
}
?>
