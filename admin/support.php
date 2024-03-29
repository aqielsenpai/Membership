<?php
session_start();
if (isset($_SESSION['sessionid'])) {
    $admin_email = $_SESSION['admin_email'];
    $admin_password = $_SESSION['admin_password'];
    $admin_id = $_SESSION['admin_id'];
} else {
    echo "<script>alert('No session available. Please login.');</script>";
    echo "<script> window.location.replace('login.php')</script>";
}


include "../dbconnect.php";
if (isset($_POST["submit"])) {
    $message_title = $_POST["message_title"];
    $message_content = $_POST["message_content"];
    $member_id = $_POST["member_id"];
    $sqlinsertmessage = "INSERT INTO `tbl_messages`( `message_title`, `message_content`, `member_id`) VALUES (  ' $message_title','$message_content','$member_id')";


    if ($conn->query($sqlinsertmessage) === TRUE) {

        echo "<script> alert(' Success')</script>";
        echo "<script> window.location.replace('support.php')</script>";
    } else {
        echo "<script> alert(' Failed')</script>";
    }
}

if (isset($_GET["operation"])) {
    $message_id = $_GET["message_id"];
    $operation = $_GET["operation"];
    if ($operation == "delete") {
        $sqldelete = "DELETE FROM `tbl_messages` WHERE message_id =  '$message_id' ";
        if ($conn->query($sqldelete) === TRUE) {

            echo "<script> alert(' Success')</script>";
            echo "<script> window.location.replace('support.php')</script>";
        } else {
            echo "<script> alert(' Failed')</script>";
        }
    }
}

$sqlloadevents = "SELECT `message_id`, `message_title`, `message_content`, `member_id`, `message_date` FROM `tbl_messages`   ";
$results_per_page = 10;
if (isset($_GET['pageno'])) {
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
} else {
    $pageno = 1;
    $page_first_result = ($pageno - 1) * $results_per_page;
}

$result = $conn->query($sqlloadevents);
$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlloadevents = $sqlloadevents . " LIMIT $page_first_result , $results_per_page ";
$result = $conn->query($sqlloadevents);
$starting_index = ($pageno - 1) * $results_per_page + 1;


?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body>
    <div class="w3-container w3-teal w3-padding-large">
        <h1>Membership</h1>
    </div>

    <div class="w3-bar w3-black">
        <a href="index.php" class="w3-bar-item w3-button w3-mobile">News</a>
        <a href="events.php" class="w3-bar-item w3-button w3-mobile">Events/Activities</a>
        <a href="resources.php" class="w3-bar-item w3-button w3-mobile">Resources</a>
        <a href="support.php" class="w3-bar-item w3-button w3-mobile">Support</a>
        <a href="profile.php" class="w3-bar-item w3-button w3-mobile">Profile</a>
        <a href="logout.php" class="w3-bar-item w3-button w3-mobile">Logout</a>
        <a class="w3-right w3-button" onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button w3-mobile"> New message</a>


    </div>

    <div class="w3-container w3-padding-large" style="height : 750px">
        <?php //table
        if ($result->num_rows > 0) {
            echo "<table class = 'w3-table w3-striped'>";
            echo "<tr><th>no</th><th>Title </th> <th>Message content</th> <th>Date</th>  <th>Operations</th> </tr>";
            $i = $starting_index;
            while ($row = $result->fetch_assoc()) {

                $date = date_create($row['message_date']);
                $mydate = date_format($date, "d/m/Y h:i a");
                $message_id = $row['message_id'];

                echo "<tr><td>$i</td>
                 <td>" . $row['message_title'] . "</td><td>" . $row['message_content'] . "</td><td>" . $mydate . "</td>";
                echo "<td> <a href = 'support.php?operation=delete&message_id=$message_id' onclick=\"return confirm('Are you sure?')\">Delete </a> &nbsp 
                 <a href = 'messages.php?message_id=$message_id' > Details </a>
                 </td> </tr>";
                $i++;
            }
            echo "</table>";
        }
        ?>
    </div>


</body>

<div id="id01" class="w3-modal">
    <div class="w3-modal-content">
        <div class="w3-container w3-padding-large">
            <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
            <h3>New Message</h3>
            <form action="support.php" method="post">
                <label>Message title :
                    <input class="w3-input w3-border w3-round" type="text" name="message_title" id="idmessage_title" placeholder="Please enter your message titile" required><br>
                </label>
                <label> Message content :
                    <textarea class="w3-input w3-border w3-round" type="text" name="message_content" id="idmessage_content" placeholder="Please enter yourmessage content" rows="10" cols="30" required></textarea><br>
                </label>
                <input type="hidden" name="member_id" value="<?php echo $member_id ?> ">
                <input class="w3-button w3-teal w3-round" type="submit" name="submit"><br>

            </form>
        </div>
    </div>


    </form>
</div>
</div>
</div>

</html>