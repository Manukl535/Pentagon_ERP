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

// Fetch unread emails for the logged-in user
$sqlInbox = "SELECT * FROM mails WHERE (to_email = ? OR cc_email = ?) AND is_read = 0";
$stmtInbox = $conn->prepare($sqlInbox);
$stmtInbox->bind_param('ss', $email, $email);
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
    <title>Inv Mails</title>
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

    <!-- Sidebar Compose, Inbox, Sent, Trash -->
    <nav class="w3-sidebar w3-bar-block w3-collapse w3-white  w3-card" style="z-index:3;width:245px;" id="mySidebar">
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-dark-grey w3-button w3-hover-black w3-left-align" onclick="document.getElementById('id01').style.display='block'">Compose <i class="w3-padding fa fa-pencil"></i></a>
        
        <a id="myBtn" onclick="myFunc('Demo1'); toggleInboxCount();" href="javascript:void(0)" class="w3-bar-item w3-button"><i class="fa fa-inbox w3-margin-right"></i>Inbox <span id="inboxCountDisplay"><?php echo count($mailsInbox) > 0 ? '(' . count($mailsInbox) . ')' : ''; ?></span><i class="fa fa-caret-down w3-margin-left"></i></a>
        <div id="Demo1" class="w3-hide w3-animate-left">
            <?php foreach ($mailsInbox as $mail): ?>
                <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" onclick="openMail('<?php echo htmlspecialchars($mail['id']); ?>');w3_close(); markAsRead('<?php echo htmlspecialchars($mail['id']); ?>');">
                    <div class="w3-container">
                        <img class=""><span class="w3-opacity w3-large"><?php echo htmlspecialchars($mail['from_email']); ?></span>
                        <p><?php echo htmlspecialchars($mail['subject']); ?></p>
                        <span class="w3-right w3-margin-right"><i class="fa fa-trash w3-text-red" onclick="deleteMail('<?php echo htmlspecialchars($mail['id']); ?>', 'inbox')"></i></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        
        <a href="#" class="w3-bar-item w3-button" onclick="myFunc('Demo2')"><i class="fa fa-paper-plane w3-margin-right"></i>Sent <i class="fa fa-caret-down w3-margin-left"></i></a>
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
        
        <a href="#" class="w3-bar-item w3-button" onclick="myFunc('Demo3')"><i class="fa fa-trash w3-margin-right"></i>Trash (<?php echo count($mailsTrash); ?>) <i class="fa fa-caret-down w3-margin-left"></i></a>
        <div id="Demo3" class="w3-hide w3-animate-left">
            <?php foreach ($mailsTrash as $mail): ?>
                <a href="javascript:void(0)" class="w3-bar-item w3-button w3-border-bottom test w3-hover-light-grey" onclick="openMail('<?php echo htmlspecialchars($mail['id']); ?>');w3_close();">
                    <div class="w3-container">
                        <img class=""><span class="w3-opacity w3-large"><?php echo htmlspecialchars($mail['from_email']); ?></span>
                        <p><?php echo htmlspecialchars($mail['subject']); ?></p>
                        <span class="w3-right w3-margin-right"><i class="fa fa-trash w3-text-red" onclick="deletePermanentlyMail('<?php echo htmlspecialchars($mail['id']); ?>', 'trash')"></i></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Compose Modal -->
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

    <!-- Reply Modal -->
<div id="id02" class="w3-modal" style="z-index:4">
    <div class="w3-modal-content w3-animate-zoom">
        <div class="w3-container w3-padding w3-red">
            <span onclick="document.getElementById('id02').style.display='none'" class="w3-button w3-red w3-right w3-xxlarge"><i class="fa fa-remove"></i></span>
            <h2>Reply to Mail</h2>
        </div>
        <div class="w3-panel">
            <form action="reply_mail.php" method="POST" class="w3-container">
                <input type="hidden" name="to_email" id="reply_to_email" required>
                <input type="hidden" name="cc_email" id="reply_cc_email">
                <input type="hidden" name="from_email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="reply_to" id="reply_to_mail_id">
                
                <label>Subject</label>
                <input class="w3-input w3-border w3-margin-bottom" type="text" name="subject" id="reply_subject" required>
                
                <textarea class="w3-input w3-border w3-margin-bottom" style="height:60px" placeholder="Write your reply here!" name="message" required></textarea>
                
                <div class="w3-section">
                    <button type="submit" class="w3-button w3-red">Send <i class="fa fa-paper-plane"></i></button>
                    <a class="w3-button w3-red" onclick="document.getElementById('id02').style.display='none'">Cancel <i class="fa fa-remove"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Mail Preview -->
    <div class="w3-main" style="margin-left:270px;">
        <i class="fa fa-bars w3-button w3-white w3-hide-large w3-xlarge w3-margin-left w3-margin-top" onclick="w3_open()"></i>
        <a href="javascript:void(0)" class="w3-hide-large w3-red w3-button w3-right w3-margin-top w3-margin-right" onclick="document.getElementById('id01').style.display='block'"><i class="fa fa-pencil"></i></a>

        <?php foreach ($mailsInbox as $mail): ?>
            <div id="<?php echo htmlspecialchars($mail['id']); ?>" class="w3-container person" style="display: none;">
                <br>
                <img class="">
                <h5 class="w3-opacity">Subject: <?php echo htmlspecialchars($mail['subject']); ?></h5>
                <h4><i class="fa fa-clock-o"></i> From <?php echo htmlspecialchars($mail['from_email']); ?>, <?php echo date('M d, Y', strtotime($mail['sent_at'])); ?></h4>
                <a class="w3-button w3-light-grey" onclick="replyToMail('<?php echo htmlspecialchars($mail['id']); ?>', '<?php echo htmlspecialchars($mail['from_email']); ?>', '<?php echo htmlspecialchars($mail['subject']); ?>')">Reply <i class="w3-margin-left fa fa-mail-reply"></i></a>
                <a class="w3-button w3-light-grey">Forward <i class="w3-margin-left fa fa-arrow-right"></i></a>
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
                <a class="w3-button w3-light-grey" onclick="replyToMail('<?php echo htmlspecialchars($mail['id']); ?>', '<?php echo htmlspecialchars($mail['from_email']); ?>', '<?php echo htmlspecialchars($mail['subject']); ?>')">Reply <i class="w3-margin-left fa fa-mail-reply"></i></a>
                <a class="w3-button w3-light-grey">Forward <i class="w3-margin-left fa fa-arrow-right"></i></a>
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
        var unreadCount = <?php echo count($mailsInbox); ?>; // Initial unread count

        function toggleInboxCount() {
            if (unreadCount > 0) {
                unreadCount--; // Decrease count when inbox is opened
                updateInboxCountDisplay();
            }
        }

        function updateInboxCountDisplay() {
            var inboxCountDisplay = document.getElementById('inboxCountDisplay');
            if (unreadCount > 0) {
                inboxCountDisplay.textContent = '(' + unreadCount + ')';
            } else {
                inboxCountDisplay.textContent = '';
            }
        }

        function markAsRead(mailId) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = this.responseText.trim();
                    if (response === "success") {
                        // Assuming you have a function to update the UI for read mails
                        // Example: markMailAsRead(mailId);
                    } else {
                        alert("Failed to mark email as read. Please try again later.");
                    }
                }
            };
            xhttp.open("POST", "mark_as_read.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("mail_id=" + mailId);
        }

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

        function deletePermanentlyMail(mailId, type) {
    if (confirm("Are you sure you want to delete this email permanently?")) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = this.responseText.trim();
                if (response === "success") {
                    document.getElementById(mailId).style.display = 'none'; // Remove mail from UI
                    alert("Email deleted permanently!");
                } else {
                    alert("Failed to delete email permanently. Please try again later.");
                }
            }
        };
        xhttp.open("POST", "delete_trash.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("mail_id=" + mailId + "&type=" + type);
    }
}
    // Function to decrease unread count (called when inbox is opened)
    function toggleInboxCount() {
        if (unreadCount > 0) {
            unreadCount--;
            updateInboxCountDisplay();
            sessionStorage.setItem('unreadCount', unreadCount); // Store updated count in sessionStorage
        }
    }

   
    // On page load, retrieve unreadCount from sessionStorage if available
    window.onload = function() {
        if (sessionStorage.getItem('unreadCount')) {
            unreadCount = parseInt(sessionStorage.getItem('unreadCount'));
            updateInboxCountDisplay();
        }
    };

    function markAsRead(mailId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = this.responseText.trim();
            if (response === "success") {
                // Update UI to mark mail as read (your implementation)
                // Example: markMailAsRead(mailId);
            } else {
                alert("Failed to mark email as read. Please try again later.");
            }
        }
    };
    xhttp.open("POST", "mark_as_read.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("mail_id=" + mailId);
}

function replyToMail(mailId, fromEmail, subject) {
    document.getElementById('reply_to_email').value = fromEmail;
    document.getElementById('reply_subject').value = 'Re: ' + subject;
    document.getElementById('reply_to_mail_id').value = mailId;
    document.getElementById('id02').style.display = 'block';
}



    </script>
</body>
</html>
