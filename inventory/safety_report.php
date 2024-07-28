<?php
session_start();
include('../Includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}


?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Report Safety Issues</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #333;
    }

    form {
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"],[type="tel"]
   {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      margin-bottom: 10px;
      rows: 10;
      columns: 500;
      resize:none;

    }

    select {
      width: 100%;
      padding: 8px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      margin-bottom: 20px; 
    }

    textarea {
      width: 100%;
      height: 200px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
      margin-bottom: 20px; 
    }


    button[type="submit"] {
      background-color: #4CAF50;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

  <div class="container">
    
  <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px;color:blue" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="index.php"><i style="font-size:24px;color:blue" class="fa">&#xf015;</i></a>
    <h1>Report Safety Issues</h1>
    <form method="post" action="safety_report_db.php">
    <form>
       <label for="name">Name:</label>
       <input type="text" id="name" name="name" required>
 
       <label for="phone">Phone:</label>
       <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" required>
 

       <label for="dept" style="display: block; margin-bottom: 10px;">Dept</label>
       <select name="dept" id="dept" style="width: 100%; padding: 8px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">

          <option value="receipt">Receipt</option>
          <option value="pickpack">Pickpack</option>
          <option value="inventory">Inventory</option>
          <option value="dispatch">Dispatch</option>
       </select>
       
       <label for="issue">Safety Issue:</label>
       <textarea id="issue" name="issue" required></textarea>
 
       <button type="submit">Submit</button>
     </form>

    <?php
    include('../includes/connection.php');

    // PHP logic to insert values into the database table
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $name = $_POST["name"];
      $phone = $_POST["phone"];
      $dept = $_POST["dept"];
      $issue = $_POST["issue"];


      // SQL query to insert values into the database table
      $sql = "INSERT INTO safety_reports (name, phone,dept, issue) VALUES ('$name', '$phone',$dept  , '$issue')";

      // Execute the query
      $result = mysqli_query($conn, $sql);

      // Close the database connection
      mysqli_close($conn);

      // Redirect to a success page or display a success message
      echo "<script>alert('Report submitted successfully!')</script>";
      header("Location: dashboard.php");
      exit();
    }
    ?>

  </div>
</body>
</html>