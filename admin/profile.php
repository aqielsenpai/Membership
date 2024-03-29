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
    $admin_id = $_POST["admin_id"];
    $admin_email = $_POST["admin_email"];
    $old_password = sha1($_POST["old_password"]);
    $new_password = sha1 ($_POST["new_password"]);
    $sqlupdateadmin = "UPDATE `tbl_admins` SET `admin_password`='$new_password' WHERE `admin_password` = '$old_password'";


    if ($conn->query($sqlupdateadmin) === TRUE) {

        echo "<script> alert(' Success')</script>";
        echo "<script> window.location.replace('profile.php')</script>";
    } else {
        echo "<script> alert(' Failed')</script>";
    }
}

if (isset($_GET["operation"])) {
    $member_id = $_GET["admin_id"];
    $operation = $_GET["operation"];
    if ($operation == "delete") {
        $sqldelete = "DELETE FROM `tbl_admins` WHERE admin_id =  '$admin_id' ";
        if ($conn->query($sqldelete) === TRUE) {

            echo "<script> alert(' Success')</script>";
            echo "<script> window.location.replace('profile.php')</script>";
        } else {
            echo "<script> alert(' Failed')</script>";
        }
    }
}


$sqlloadmember = "SELECT * FROM `tbl_admins` ";
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
        <a class="w3-right w3-button" onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button w3-mobile"> Edit profile</a>
    </div>

    <div class="w3-container w3-padding-large" style = "height : 750px ; max-width : 600px; margin:auto ;">
        <h3>Admin Profile</h3>
        <?php
        if ($result->num_rows > 0) {
            echo "<table class = 'w3-table w3-striped'>";
           
            $i = $starting_index;
            while ($row = $result->fetch_assoc()) {
                
                $date = date_create($row['admin_datereg']);
                $mydate = date_format($date, "d/m/Y h:i a");
                $admin_id = $row['admin_id'];
                $admin_email = $row['admin_email'];
                echo "<tr><td>admin_id</td><td>$admin_id</td></tr>";
                echo "<tr><td>email</td><td>$admin_email</td></tr>";
                echo "<tr><td>date register</td><td>$mydate</td></tr>";
                echo "<tr><td>password</td><td>***</td></tr>";


                // echo "<tr><td>$i</td>
                //  <td>" . $row['admin_id'] . "</td> <td>" . $row['admin_email'] . "</td> <td> *** </td> <td>" . $mydate . "</td>";
                // echo "<td> <a href = 'profile.php?operation=delete&admin_id=$admin_id' onclick=\"return confirm('Are you sure?')\">Delete </a> </td> </tr>";
                // $i++; 
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
                echo '<a href = "profile.php?pageno=' . $page . '" style = "text-decoration: none;" >&nbsp&nbsp' . $page . ' </a>';
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
            <h3>Edit profile</h3>
            <form action="profile.php" method="post" >
                <!-- edit profile button  -->
                <div class="w3-container">
                    
                    <form class="w3-container" action="profile.php" method="post">
                    
                    <input type="hidden" id="adminid" name="admin_id" value= <?php echo $admin_id ?> >
                    <input type="hidden" id="adminemail" name="admin_email" value= <?php echo $admin_email ?> >
                        <label for="old-password">Old Password:</label>
                        <input class="w3-input" type="password" id="old_password" name="old_password">
                        
                        <label for="new-password">New Password:</label>
                        <input class="w3-input" type="password" id="new_password" name="new_password">
                        

                        <button class="w3-button w3-blue w3-margin-top" type="submit" name = "submit" >Change Password</button>
                    </form>
                </div>

            </form>
        </div>
    </div>


    </form>
</div>
</div>
</div>