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
    $fullname = $_POST["member_fullname"];
    $membership = $_POST["member_membership"];
    $member_email = $_POST["member_email"];
    $member_phonenumber = $_POST["member_phonenumber"];
    $sqlinsertmember = "INSERT INTO `tbl_members`( `member_id`, `member_fullname`, `member_membership` , `member_email` , `member_phonenumber`) VALUES ( ' $member_id', ' $fullname','$membership','$member_email','$member_phonenumber')";


    if ($conn->query($sqlinsertmember) === TRUE) {

        echo "<script> alert(' Success')</script>";
        echo "<script> window.location.replace('membership.php')</script>";
    } else {
        echo "<script> alert(' Failed')</script>";
    }
}

if (isset($_GET["operation"])) {
    $member_id = $_GET["member_id"];
    $operation = $_GET["operation"];
    if ($operation == "delete") {
        $sqldelete = "DELETE FROM `tbl_members` WHERE member_id =  '$member_id' ";
        if ($conn->query($sqldelete) === TRUE) {

            echo "<script> alert(' Success')</script>";
            echo "<script> window.location.replace('membership.php')</script>";
        } else {
            echo "<script> alert(' Failed')</script>";
        }
    }
}


$sqlloadmember = "SELECT * FROM `tbl_members` ";
$results_per_page = 10;
if (isset($_GET['pageno'])) {
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
} else {
    $pageno = 1;
    $page_first_result = ($pageno - 1) * $results_per_page;
}

$result = $conn->query($sqlloadmember);
$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlloadmember = $sqlloadmember . " LIMIT $page_first_result , $results_per_page ";
$result = $conn->query($sqlloadmember);
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
        <a href="membership.php" class="w3-bar-item w3-button w3-mobile">Membership</a>
        <a href="profile.php" class="w3-bar-item w3-button w3-mobile">Profile</a>
        <a href="logout.php" class="w3-bar-item w3-button w3-mobile">Logout</a>
        <a class="w3-right w3-button" onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button w3-mobile">Add new membership</a>
    </div>

    <div class="w3-container w3-padding-large" style="height : 750px">
        <?php
        if ($result->num_rows > 0) {
            echo "<table class = 'w3-table w3-striped'>";
            echo "<tr> <th>no</th> <th>user id</th> <th>Full name</th> <th>Type of membership</th> <th>phone number</th> <th>member email</th> <th>member reg date</th>  <th>operations</th>  </tr>";
            $i = $starting_index;
            while ($row = $result->fetch_assoc()) {


                $member_id = $row['member_id'];
                $date = date_create($row['member_regdate']);
                $mydate = date_format($date, "d/m/Y h:i a");
                //table

                echo "<tr><td>$i</td>
                <td> " . $row['member_id'] . "</td> <td> " . $row['member_fullname'] . "</td>   <td>" . $row['member_membership'] . "</td>  <td>" . $row['member_phonenumber'] . "</td> <td>" . $row['member_email'] . "</td> <td>  $mydate   </td>";
                echo "<td> <a href = 'membership.php?operation=delete&member_id=$member_id' onclick=\"return confirm('Are you sure?')\">Delete </a> </td> </tr>";
                $i++;
            }
            echo "</table>";
        }
        ?>
    </div>

    <div class="w3-container w3-padding-large">
        <?php
        $num = 1;
        if ($pageno == 1) {
            $num = 1;
        } else if ($pageno == 2) {
            $num = ($num) + 10;
        } else {
            $num = $pageno * 10 - 9;
        }
        echo "<div class=''>";
        echo "<center>";
        for ($page = 1; $page <= $number_of_page; $page++) {
            echo '<a href = "membership.php?pageno=' . $page . '" style = "text-decoration: none;" >&nbsp&nbsp' . $page . ' </a>';
        }
        echo " ( " . $pageno . " )";
        echo "</center>";
        echo "</div>";
        ?>

    </div>




</body>

<div id="id01" class="w3-modal">
    <div class="w3-modal-content">
        <div class="w3-container w3-padding-large">
            <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
            <h3>Add new member</h3>
            <form action="membership.php" method="post" enctype="multipart/form-data">
                <label>Full name :
                    <input class="w3-input w3-border w3-round" type="text" name="member_fullname" id="idname" placeholder="Please enter your full name" required><br>
                </label>
                <label> Phone number :
                    <input class="w3-input w3-border w3-round" type="text" name="member_phonenumber" id="idphone" placeholder="Please enter your phone number" required><br>
                </label>

                <label for="membership">Choose membership:<br><br>

                    <select class="w3-input" name="member_membership" id="idmembership">
                        <option value="full">Full Membership</option>
                        <option value="student">Student Membership</option>
                        <option value="associate">Associate Membership</option>
                    </select>
                </label><br>

                <label> Upload CV :
                    <input class="w3-button w3-block w3-round" name="fileToUpload" type="file" id="idtitle" required accept="application/pdf">
                </label>


                <label> Email :
                    <input class="w3-input w3-border w3-round" type="email" name="member_email" id="idemail" placeholder="Please enter your email address" required><br>
                </label>
                <label>Password :
                    <input class="w3-input w3-border w3-round" name="password" id="password" placeholder="Please create a new password" type="password" required onkeyup='check();' />
                </label>
                <br>
                <label>confirm password:
                    <input class="w3-input w3-border w3-round" type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required onkeyup='check();' />
                    <span id='message'></span>
                </label><br>
                <input class="w3-button w3-teal w3-round" type="submit" name="submit"><br>
            </form>
        </div>
    </div>


    </form>
</div>
</div>
</div>

</html>