<?php
session_start();
if (isset($_SESSION['sessionid'])) {
    $member_email = $_SESSION['member_email'];
    $member_fullname = $_SESSION['member_fullname'];
    $member_id = $_SESSION['member_id'];
    $member_phonenumber = $_SESSION['member_phonenumber'];
} else {
    echo "<script>alert('No session available. Please login.');</script>";
    echo "<script> window.location.replace('login.php')</script>";
}

include "../dbconnect.php";

if (isset($_GET['message_id'])) {
    $message_id = $_GET['message_id'];
    $sqlmessage = "SELECT * FROM `tbl_messages` WHERE message_id = $message_id";
    $result = $conn->query($sqlmessage);
    $number_of_result = $result->num_rows;

    $sqlmessage2 = "SELECT * FROM `tbl_messagedetails` WHERE message_id = $message_id";
    $result2 = $conn->query($sqlmessage2);
    $number_of_result2 = $result2->num_rows;
}

if (isset($_POST["submit"])) {
    $message_id = $_POST["message_id"];
    $member_id = $_POST["member_id"];
    $messagedetails_content = $_POST["messagedetails_content"];
    $sqlinsertmessage = "INSERT INTO `tbl_messagedetails`(`message_id`, `messagedetails_content`, `member_id`) VALUES ( ' $message_id', ' $messagedetails_content', ' $member_id')";
    if ($conn->query($sqlinsertmessage) === TRUE) {

        echo "<script> alert(' Success')</script>";
        echo "<script> window.location.replace('messages.php?message_id=$message_id')</script>";
    } else {
        echo "<script> alert(' Failed')</script>";
    }
}






?>
<html>

<head>
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../style.css">
    <script type="text/javascript" src="../myscript.js"></script>

</head>

<body>
    <div class="w3-container w3-teal w3-padding-large">
        <h1>Membership</h1>
        <a class="w3-teal w3-right w3-button" onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button w3-mobile">Reply</a>
    </div>

    <div style="min-height:100vh;overflow-y: auto;">
        <div class="w3-container w3-padding-large">
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $message_id = $row['message_id'];
                    $message_title = $row['message_title'];
                    $message_content = $row['message_content'];
                    $member_id = $row['member_id'];

                    $date = date_create($row['message_date']);
                    $mydate = date_format($date, "d/m/Y h:i a");
                    echo "<div class='w3-card w3-container w3-margin' style = 'max-width:800px'> <h4> $message_title </h4><br> $mydate <p>$message_content</p> </div>";
                }
            }
            ?>
        </div>
        <div class="w3-container w3-padding-large">
            <?php if ($result2->num_rows > 0) {
                while ($row = $result2->fetch_assoc()) {
                    $message_id2 = $row['message_id'];
                    $messagedetails_content = $row['messagedetails_content'];
                    $message_date = $row['message_date'];

                    $date2 = date_create($row['message_date']);
                    $mydate2 = date_format($date2, "d/m/Y h:i a");
                    echo "<div class='w3-card w3-container w3-margin' style = 'max-width:800px'> $messagedetails_content   <p> $mydate2</p> </div>";
                }
            }
            ?>
        </div>



    </div>
    <footer> MEMBERSHIP SYSTEM COPYRIGHT </footer>

</body>
<div id="id01" class="w3-modal">
    <div class="w3-modal-content">
        <div class="w3-container w3-padding-large">
            <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
            <h3>Reply message</h3>
            <form action="messages.php" method="post">
                <!--   -->

                <textarea class="w3-input w3-border w3-round" type="text" name="messagedetails_content" id="idmessage" placeholder="Please enter your message" rows="10" cols="30" required></textarea><br>
                <input type="hidden" name="message_id" value="<?php echo $message_id ?>"><br>
                <input type="hidden" name="member_id" value="<?php echo $member_id ?>"><br>
                <input class="w3-button w3-teal w3-round" type="submit" name="submit"><br>




            </form>
        </div>
    </div>

</html>