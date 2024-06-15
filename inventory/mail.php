<!-- Designed and Developed by Manu-->
<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: .../index.php");
    exit(); // Ensure script stops executing after redirection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mails</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles00.css">
    <link rel="stylesheet" href="styles1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
      <!-- Sidebar Compose,Inbox,Sent,Draft,Trash -->

    <nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
       
      
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-black w3-left-align" onclick="document.getElementById('id01').style.display='block'">New Message <i class="w3-padding fa fa-pencil"></i></a>
       
        
        <a id="myBtn" onclick="myFunc('Demo1')" href="javascript:void(0)" class="w3-bar-item w3-button"><i class="fa fa-inbox w3-margin-right"></i>Inbox (1)<i class="fa fa-caret-down w3-margin-left"></i></a>
        <div id="Demo1" class="w3-hide w3-animate-left">
            <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" onclick="openMail('Manu');w3_close();">
                <div class="w3-container">
                    <img class=""><span class="w3-opacity w3-large">Manu</span>
                    <p>Welcome!</p>
                </div>
            </a>
            
            <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" onclick="openMail('Another');w3_close();">
                <div class="w3-container">
                    <img class=""><span class="w3-opacity w3-large">Another</span>
                    <p>Another welcome message!</p>
                </div>
            </a>
        </div>


        <a href="#" class="w3-bar-item w3-button"><i class="fa fa-paper-plane w3-margin-right"></i>Sent</a>
        <a href="#" class="w3-bar-item w3-button"><i class="fa fa-hourglass-end w3-margin-right"></i>Drafts</a>
        <a href="#" class="w3-bar-item w3-button"><i class="fa fa-trash w3-margin-right"></i>Trash</a>
    </nav>


    <!-- Modal content -->
     
    <div id="id01" class="w3-modal" style="z-index:4">
        <div class="w3-modal-content w3-animate-zoom">
            <div class="w3-container w3-padding w3-red">
                <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-red w3-right w3-xxlarge"><i class="fa fa-remove"></i></span>
                <h2>Send Mail</h2>
            </div>
            <div class="w3-panel">
    <form action="sendmail.php" method="POST" class="w3-container">
        <label>To</label>
        <input class="w3-input w3-border w3-margin-bottom" type="email" name="to_email" required>
        
        <label>CC</label>
        <input class="w3-input w3-border w3-margin-bottom" type="email" name="cc_email">
        
        <label>From</label>
        <input class="w3-input w3-border w3-margin-bottom" type="email" name="from_email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" readonly>
        
        <label>Subject</label>
        <input class="w3-input w3-border w3-margin-bottom" type="text" name="subject" required>
        
        <textarea class="w3-input w3-border w3-margin-bottom" style="height:60px" placeholder="Write Something here!" name="message" required></textarea>
        
        <div class="w3-section">
            <button type="submit" class="w3-button w3-red">Send <i class="fa fa-paper-plane"></i></button>
            <a class="w3-button w3-red" onclick="document.getElementById('id01').style.display='none'">Cancel <i class="fa fa-remove"></i></a>
        </div>
    </form>
</div>

        </div>
    </div>

    <!-- Mail Preview -->

    <div class="w3-main" style="margin-left:320px;">
        <i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>
        <a href="javascript:void(0)" class="w3-hide-large w3-red w3-button w3-right w3-margin-top w3-margin-right" onclick="document.getElementById('id01').style.display='block'"><i class="fa fa-pencil"></i></a>

        <div id="Manu" class="w3-container person">
            <br>
            <img class="">
            <h5 class="w3-opacity">Subject: First Mail</h5>
            <h4><i class="fa fa-clock-o"></i> From Manu , Jun 14, 2024.</h4>
            <a class="w3-button w3-light-grey">Reply<i class="w3-margin-left fa fa-mail-reply"></i></a>
            <a class="w3-button w3-light-grey">Forward<i class="w3-margin-left fa fa-arrow-right"></i></a>
            <hr>
            <p>Welcome.</p>
            <p>That's it!</p>
        </div>

      
        <div id="Another" class="w3-container person" style="display: none;">
            <br>
            <img class="">
            <h5 class="w3-opacity">Subject: Another Mail</h5>
            <h4><i class="fa fa-clock-o"></i> From Another , Jun 15, 2024.</h4>
            <a class="w3-button w3-light-grey">Reply<i class="w3-margin-left fa fa-mail-reply"></i></a>
            <a class="w3-button w3-light-grey">Forward<i class="w3-margin-left fa fa-arrow-right"></i></a>
            <hr>
            <p>Another welcome message.</p>
            <p>That's it!</p>
        </div>
    </div>

 
    <script src="script.js"></script>
</body>
</html>
