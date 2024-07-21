<?php
session_start();

// Check if user is already logged in, redirect to dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

// Initialize variables
$error_message = '';
$barcode = '';

// Database connection details (include your connection script)
include('../Includes/connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username (barcode) from POST
    $barcode = $_POST['username'];

    // SQL query to retrieve username from associates table
    $sql = "SELECT username FROM associates WHERE barcode = ?";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $barcode); // Bind parameters
    
    // Execute statement
    $stmt->execute();
    
    // Store result
    $stmt->store_result();
    
    // Check if a row is returned
    if ($stmt->num_rows > 0) {
        // Valid barcode found, set session variable
        $stmt->bind_result($username);
        $stmt->fetch();
        
        $_SESSION['username'] = $username;

        // Redirect to dashboard.php upon successful login
        header("Location: dashboard.php");
        exit();
    } else {
        // Invalid barcode, set error message
        $error_message = "Invalid barcode. Please try again.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style>
    body {
        color: #999;
        background: #63738a;
        font-family: 'Varela Round', sans-serif;
    }
    .form-control {
        box-shadow: none;
        border-color: #ddd;
    }
    .form-control:focus {
        border-color: #4aba70; 
    }
    .login-form {
        width: 400px;
        margin: 0 auto;
        padding: 30px 0;
    }
    .login-form form {
        color: #434343;
        border-radius: 1px;
        margin-bottom: 15px;
        background: #fff;
        border: 1px solid #f3f3f3;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
        position: relative;
    }
    .login-form h4 {
        text-align: center;
        font-size: 22px;
        margin-bottom: 20px;
    }
    .login-form .form-group {
        margin-bottom: 20px;
    }
    .login-form .form-control, .login-form .btn {
        min-height: 40px;
        border-radius: 2px; 
        transition: all 0.5s;
    }
    .login-form .btn {
        background: #5cb85c;
        border: none;
        line-height: normal;
    }
    .login-form .btn:hover, .login-form .btn:focus {
        background: #42ae68;
    }
    .id-icon {
        height: 100px;
        width: 100px;
        display: block;
        margin: 0 auto;
    }
    footer {
        text-align: center;
        padding-top: 20px;
        color: #fff;
    }
    .error-message {
        color: red;
        font-size: 14px;
        text-align: center;
        margin-top: 5px;
    }
</style>
</head>
<body>
<div class="login-form">    
    <form id="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <img src="id_card.png" class="id-icon" alt="ID Card Icon">
        <h4><b>Login</b></h4>
        <?php if(!empty($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Scan Barcode" id="username" name="username" required="required" <?php if(isset($_POST['username']) && !empty($error_message)) echo 'value="' . htmlspecialchars($_POST['username']) . '"'; ?>>
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-lg">Login</button>             
    </form>			
</div>
<footer>
    <p>2024 &copy; All Rights Reserved</p>
    <p>Developed and Maintained by <b>Pentagon</b></p>
</footer>
</body>
</html>
