<?php
session_start();
include('../includes/connection.php');

// Check if user is logged in and session variable is set
if (!isset($_SESSION['email'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: ../index.php");
    exit(); // Ensure script stops executing after redirection
}

$email = $_SESSION['email'];
$sqlInbox = "SELECT * FROM mails WHERE to_email = ? OR cc_email = ?";
$stmtInbox = $conn->prepare($sqlInbox);
$stmtInbox->bind_param('ss', $email, $email); // Binding $email twice for both parameters
$stmtInbox->execute();
$resultInbox = $stmtInbox->get_result();
$mailsInbox = $resultInbox->fetch_all(MYSQLI_ASSOC);


// Fetch sent emails for the logged-in user
$sqlSent = "SELECT * FROM mails WHERE from_email = ?";
$stmtSent = $conn->prepare($sqlSent);
$stmtSent->bind_param('s', $email);
$stmtSent->execute();
$resultSent = $stmtSent->get_result();
$mailsSent = $resultSent->fetch_all(MYSQLI_ASSOC);

// Fetch trash emails for the logged-in user from separate table
$sqlTrash = "SELECT * FROM trash WHERE to_email = ?";
$stmtTrash = $conn->prepare($sqlTrash);
$stmtTrash->bind_param('s', $email);
$stmtTrash->execute();
$resultTrash = $stmtTrash->get_result();
$mailsTrash = $resultTrash->fetch_all(MYSQLI_ASSOC);

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

    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #3498db;
            color: #fff;
            border: none;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #2980b9;
        }
        hr {
            border: none;
            height: 1px;
            background-color: #3498db; 
            margin: 20px auto; 
        }
    </style>

</head>
<body>
    <!-- Main Content -->
    <main>
        <div class="nav-item">
            <button class="button"><a href="./index.php" style="text-decoration:none;"><i class="fa fa-home" style="font-size:15px;"></i> Home</a></button>
            <button class="button"><a href="../logout.php" style="text-decoration:none;">Logout <i class="fa fa-sign-out" style="font-size:15px;"></i></a></button>
        </div>
    </main>
    

    <!-- Sidebar Compose,Inbox,Sent,Draft,Trash -->
    <nav class="w3-sidebar w3-bar-block w3-collapse w3-white w3-animate-left w3-card" style="z-index:3;width:320px;" id="mySidebar">
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-black w3-left-align" onclick="document.getElementById('id01').style.display='block'">Compose<i class="w3-padding fa fa-pencil"></i></a>
        
        <a id="myBtn" onclick="myFunc('Demo1'); toggleInboxCount();" href="javascript:void(0)" class="w3-bar-item w3-button"><i class="fa fa-inbox w3-margin-right"></i>Inbox <span id="inboxCountDisplay">(<?php echo count($mailsInbox); ?>)</span><i class="fa fa-caret-down w3-margin-left"></i></a>
<div id="Demo1" class="w3-hide w3-animate-left">
    <?php foreach ($mailsInbox as $mail): ?>
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" onclick="openMail('<?php echo htmlspecialchars($mail['id']); ?>');w3_close();">
            <div class="w3-container">
                <img class=""><span class="w3-opacity w3-large"><?php echo htmlspecialchars($mail['from_email']); ?></span>
                <p><?php echo htmlspecialchars($mail['subject']); ?></p>
                <span class="w3-right w3-margin-right"><i class="fa fa-trash w3-text-red" onclick="deleteMail('<?php echo htmlspecialchars($mail['id']); ?>', 'inbox')"></i></span>
            </div>
        </a>
    <?php endforeach; ?>
</div>        
        <a href="#" class="w3-bar-item w3-button" onclick="myFunc('Demo2')"><i class="fa fa-paper-plane w3-margin-right"></i>Sent<i class="fa fa-caret-down w3-margin-left"></i></a>
        <div id="Demo2" class="w3-hide w3-animate-left">
            <?php foreach ($mailsSent as $mail): ?>
                <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" onclick="openMail('<?php echo htmlspecialchars($mail['id']); ?>');w3_close();">
                    <div class="w3-container">
                        <img class=""><span class="w3-opacity w3-large"><?php echo htmlspecialchars($mail['to_email']); ?></span>
                        <p><?php echo htmlspecialchars($mail['subject']); ?></p>
                        <span class="w3-right w3-margin-right"><i class="fa fa-trash w3-text-red" onclick="deleteMail('<?php echo htmlspecialchars($mail['id']); ?>', 'sent')"></i></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <a href="#" class="w3-bar-item w3-button" onclick="myFunc('Demo3')"><i class="fa fa-trash w3-margin-right"></i>Trash (<?php echo count($mailsTrash); ?>)<i class="fa fa-caret-down w3-margin-left"></i></a>
        <div id="Demo3" class="w3-hide w3-animate-left">
            <?php foreach ($mailsTrash as $mail): ?>
                <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" onclick="openMail('<?php echo htmlspecialchars($mail['id']); ?>');w3_close();">
                    <div class="w3-container">
                        <img class=""><span class="w3-opacity w3-large"><?php echo htmlspecialchars($mail['from_email']); ?></span>
                        <p><?php echo htmlspecialchars($mail['subject']); ?></p>
                        <span class="w3-right w3-margin-right"><i class="fa fa-trash w3-text-red" onclick="deleteMail('<?php echo htmlspecialchars($mail['id']); ?>', 'trash')"></i></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
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

        <?php foreach ($mailsInbox as $mail): ?>
            <div id="<?php echo htmlspecialchars($mail['id']); ?>" class="w3-container person" style="display: none;">
                <br>
                <img class="">
                <h5 class="w3-opacity">Subject: <?php echo htmlspecialchars($mail['subject']); ?></h5>
                <h4><i class="fa fa-clock-o"></i> From <?php echo htmlspecialchars($mail['from_email']); ?>, <?php echo date('M d, Y', strtotime($mail['sent_at'])); ?></h4>
                <a class="w3-button w3-light-grey">Reply<i class="w3-margin-left fa fa-mail-reply"></i></a>
                <a class="w3-button w3-light-grey">Forward<i class="w3-margin-left fa fa-arrow-right"></i></a>
                <hr>
                <p><?php echo htmlspecialchars($mail['message']); ?></p>
            </div>
        <?php endforeach; ?>

        <?php foreach ($mailsSent as $mail): ?>
            <div id="<?php echo htmlspecialchars($mail['id']); ?>" class="w3-container person" style="display: none;">
                <br>
                <img class="">
                <h5 class="w3-opacity">Subject: <?php echo htmlspecialchars($mail['subject']); ?></h5>
                <h4><i class="fa fa-clock-o"></i> To <?php echo htmlspecialchars($mail['to_email']); ?>, <?php echo date('M d, Y', strtotime($mail['sent_at'])); ?></h4>
                <a class="w3-button w3-light-grey">Reply<i class="w3-margin-left fa fa-mail-reply"></i></a>
                <a class="w3-button w3-light-grey">Forward<i class="w3-margin-left fa fa-arrow-right"></i></a>
                <hr>
                <p><?php echo htmlspecialchars($mail['message']); ?></p>
            </div>
        <?php endforeach; ?>

        <?php foreach ($mailsTrash as $mail): ?>
            <div id="<?php echo htmlspecialchars($mail['id']); ?>" class="w3-container person" style="display: none;">
                <br>
                <img class="">
                <h5 class="w3-opacity">Subject: <?php echo htmlspecialchars($mail['subject']); ?></h5>
                <h4><i class="fa fa-clock-o"></i> From <?php echo htmlspecialchars($mail['from_email']); ?>, <?php echo date('M d, Y', strtotime($mail['deleted_at'])); ?></h4>
                <a class="w3-button w3-light-grey" onclick="restoreMail('<?php echo htmlspecialchars($mail['id']); ?>')"><i class="w3-margin-left fa fa-undo"></i> Restore</a>
                <hr>
                <p><?php echo htmlspecialchars($mail['message']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="script.js"></script>
    <script>
        function deleteMail(mailId, type) {
            if (confirm("Are you sure you want to delete this email?")) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var response = this.responseText.trim();
                        if (response == "success") {
                            document.getElementById(mailId).style.display = 'none'; // Remove mail from UI
                            alert("Email deleted successfully!");
                        } else {
                            alert("Failed to delete email. Please try again later.");
                        }
                    }
                };
                xhttp.open("POST", "delete_mail.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("mail_id=" + mailId + "&type=" + type);
            }
        }

        function restoreMail(mailId) {
            if (confirm("Are you sure you want to restore this email?")) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var response = this.responseText.trim();
                        if (response === "success") {
                            document.getElementById(mailId).style.display = 'none'; // Remove mail from UI
                            alert("Email restored successfully!");
                        } else {
                            alert("Failed to restore email. Please try again later.");
                        }
                    }
                };
                xhttp.open("POST", "restore_mail.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("mail_id=" + mailId + "&type=trash");
            }
        }


        var inboxOpen = false;

function toggleInboxCount() {
    inboxOpen = !inboxOpen;
    if (inboxOpen) {
        document.getElementById('inboxCountDisplay').style.display = 'none';
    } else {
        document.getElementById('inboxCountDisplay').style.display = 'inline';
    }
}
    </script>
</body>
</html>