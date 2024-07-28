<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $newPassword = $_POST['new_password'];
    $newName = $_POST['new_name'];
    $newPhone = $_POST['new_phone'];
    
    // Update the credentials table
    $updateStmt = $conn->prepare("UPDATE credentials SET password = ?, name = ?, phone = ? WHERE user_id = ?");
    $updateStmt->bind_param("sssi", $newPassword, $newName, $newPhone, $_SESSION['user_id']);

    if ($updateStmt->execute()) {
        echo '<p style="color: green;">Account updated successfully!</p>';
        echo '<script>alert("Account info updated! Logging out..."); window.location.href = "../logout.php";</script>';
        exit; // To prevent further execution
    } else {
        echo '<p style="color: red;">Error updating account. Please try again.</p>';
    }

    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pick Pack - Account Settings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<body>

<div class="container">
<a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
    <center>
        <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue;" class="fa">&#xf015;</i></a>
        <br/>
    </center>
    <h2>Account Settings</h2>
 
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
        </div>

        <div class="form-group">
            <label for="new_name">New Name:</label>
            <input type="text" name="new_name" required>
        </div>


        <div class="form-group">
            <label for="new_phone">New Phone:</label>
            <input type="text" name="new_phone" required>
        </div>

        <button type="submit">Update Account</button>
    </form>
</div>

</body>
</html>
