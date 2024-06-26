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
if (isset($_POST["submit"]) && $_POST["submit"]=="insert") {
    $title = $_POST["title"];
    $news = $_POST["news"];
    $sqlinsertnews = "INSERT INTO `tbl_news`( `news_title`, `news_desc`) VALUES ('$title','$news')";


    if ($conn->query($sqlinsertnews) === TRUE) {

        echo "<script> alert(' Success')</script>";
        echo "<script> window.location.replace('index.php')</script>";
    } else {
        echo "<script> alert(' Failed')</script>";
    }
}

if (isset($_POST["submit"]) && $_POST["submit"]=="update") {
    $title = $_POST["title"];
    $news = $_POST["news"];
    $newsid = $_POST["newsid"];

    $sqlupdatenews = "UPDATE `tbl_news` SET `news_title`='$title',`news_desc`='$news' WHERE news_id = '$newsid'" ;


    if ($conn->query($sqlupdatenews) === TRUE) {

        echo "<script> alert(' Success')</script>";
        echo "<script> window.location.replace('index.php')</script>";
    } else {
        echo "<script> alert(' Failed')</script>";
    }
}

if (isset($_GET["operation"])) {
    $newsid = $_GET["newsid"];
    $operation = $_GET["operation"];
    if ($operation == "delete") {
        $sqldelete = "DELETE FROM `tbl_news` WHERE news_id =  '$newsid' ";
        if ($conn->query($sqldelete) === TRUE) {

            echo "<script> alert(' Success')</script>";
            echo "<script> window.location.replace('index.php')</script>";
        } else {
            echo "<script> alert(' Failed')</script>";
        }
    }
}


$sqlloadnews = "SELECT `news_id`, `news_title`, `news_desc`, `news_date` FROM `tbl_news` ORDER BY news_date DESC ";
$results_per_page = 10;
if (isset($_GET['pageno'])) {
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
} else {
    $pageno = 1;
    $page_first_result = ($pageno - 1) * $results_per_page;
}

$result = $conn->query($sqlloadnews);
$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlloadnews = $sqlloadnews . " LIMIT $page_first_result , $results_per_page ";
$result = $conn->query($sqlloadnews);
$starting_index = ($pageno - 1) * $results_per_page + 1;

function truncate($string, $length, $dots = "...")
{
    return strlen($string) > $length
        ? substr($string, 0, $length - strlen($dots)) . $dots
        : $string;
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../style.css">

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
        <a class="w3-right w3-button" onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button w3-mobile">Add news</a>
    </div>

    <div class="w3-container w3-padding-large" style="height : 750px">
        <?php
        if ($result->num_rows > 0) {
            echo "<table class = 'w3-table w3-striped'>";
            echo "<tr><th>no</th><th>Title </th> <th>News</th> <th>Date</th><th>operations</th></tr>";
            $i = $starting_index;
            $j = 0;
            while ($row = $result->fetch_assoc()) {

                $date = date_create($row['news_date']);
                $mydate = date_format($date, "d/m/Y h:i a");
                $newsid = $row['news_id'];
                $description = truncate($row['news_desc'], 100);
                $ntitle = $row['news_title'];
                $fulldescription = $row['news_desc'];

                echo "<tr><td>$i</td>
                 <td>" . $row['news_title'] . "</td><td>" . $description . "</td><td>" . $mydate . "</td>";
                echo "<td> <a href = 'index.php?operation=delete&newsid=$newsid' onclick=\"return confirm('Are you sure?')\">Delete </a> 
                <br> <a href = '' onclick=\"document.getElementById('newsdetails$j').style.display='block';return false;\">view</a> 
                <br> <a href = '' onclick=\"document.getElementById('newsedit$j').style.display='block';return false;\">edit</a> </td> </tr>";
                $i++;
                
                // Modal window for news details
                echo "<div id='newsdetails$j' class='w3-modal w3-animate-opacity'>";
                echo "<div class='w3-modal-content' style='width:60%'>";
                echo "<header class='w3-container w3-teal'>";
                echo "<span onclick=\"document.getElementById('newsdetails$j').style.display='none'\" class='w3-button w3-display-topright w3-large'>&times;</span>";
                echo "<h4>News Details</h4>";
                echo "</header>";
                echo "<h3 class='w3-container'>$ntitle</h3>";
                echo "<div class='w3-container'>Published on: $mydate</div><hr>";
                echo "<div class='w3-padding' style='text-align: justify;'>$fulldescription</div>";
                echo "</div>";
                echo "</div>";
                //

                // Modal window for news edit
                echo "<div id='newsedit$j' class='w3-modal w3-animate-opacity'>";
                echo "<div class='w3-modal-content' style='width:60%'>";
                echo "<header class='w3-container w3-teal'>";
                echo "<span onclick=\"document.getElementById('newsedit$j').style.display='none'\" class='w3-button w3-display-topright w3-large'>&times;</span>";
                echo "<h4>News Edit</h4>";
                echo "</header>";
                echo "<div class='w3-container w3-padding-large'>";
                echo "<form method='post' action='index.php'>";
                echo "<input class=\"w3-input w3-border w3-round\" type=\"text\" name=\"title\" id=\"idtitle\" placeholder=\"Enter news title\" value = '$ntitle' required><br>";
                echo "<textarea class=\"w3-input w3-border w3-round\" type=\"text\" name=\"news\" id=\"idnews\" placeholder=\"Please enter your news\" rows=\"10\" cols=\"30\" required> $fulldescription</textarea><br>";
                echo "<input type = 'hidden' name='newsid' value = '$newsid'>";
                echo "<br><input class=\"w3-button w3-teal w3-round\" type=\"submit\" name=\"submit\" value = 'update'>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";

                $j++;
                
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
            echo '<a href = "index.php?pageno=' . $page . '" style = "text-decoration: none;" >&nbsp&nbsp' . $page . ' </a>';
        }
        echo " ( " . $pageno . " )";
        echo "</center>";
        echo "</div>";
        ?>

    </div>



    <footer class="w3-center w3-padding-large">MEMBERSHIP SYSTEM COPYRIGHT</footer>
</body>
<div id="id01" class="w3-modal">
    <div class="w3-modal-content">
        <div class="w3-container w3-padding-large">
            <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
            <h3>New News</h3>
            <form method="post" action="index.php">
                <input class="w3-input w3-border w3-round" type="text" name="title" id="idtitle" placeholder="Enter news title" required><br>
                <textarea class="w3-input w3-border w3-round" type="text" name="news" id="idnews" placeholder="Please enter your news" rows="10" cols="30" required></textarea><br>
                <br>
                <input class="w3-button w3-teal w3-round" type="submit" name="submit" value = "insert">



            </form>
        </div>
    </div>
</div>

</html>