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
if (isset($_GET['search'])) {
    $searchtext = $_GET['searchtext'];
    $option = $_GET['option'];
    if ($option == "title") {
        $sqlloadnews = "SELECT `news_id`, `news_title`, `news_desc`, `news_date` FROM `tbl_news` WHERE news_title LIKE '%$searchtext%' ";
    }
    if ($option == "news") {
        $sqlloadnews = "SELECT `news_id`, `news_title`, `news_desc`, `news_date` FROM `tbl_news` WHERE news_desc LIKE '%$searchtext%' ";
    }
} else {
    $sqlloadnews = "SELECT `news_id`, `news_title`, `news_desc`, `news_date` FROM `tbl_news` ";
}


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


?>



<!DOCTYPE html>
<html>

<head>
    <title>My Basic Page</title>
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
        <a href="profile.php" class="w3-bar-item w3-button w3-mobile">Profile</a>
        <a href="logout.php" class="w3-bar-item w3-button w3-mobile">Logout</a>
    </div>

    <form action="index.php" method="get">
        <div class="w3-row w3-container w3-card w3-round w3-margin w3-center">
            <div class="w3-third w3-container w3-padding">
                <input class="w3-input" name="searchtext" placeholder="enter your search here">
            </div>
            <div class="w3-third w3-container w3-padding">
                <select class="w3-input" name="option">
                    <option value="title">Title</option>
                    <option value="news">News</option>
                </select>
            </div>
            <div class="w3-third w3-container w3-padding ">
                <button class="w3-button w3-teal " name="search" value="search"> Search</button>
            </div>
        </div>
    </form>

    <div class="w3-container w3-padding-large">
        <?php
        if ($result->num_rows > 0) {
            echo "<table class = 'w3-table w3-striped'>";
            echo "<tr>   <th>no</th>  <th>Title</th> <th>News</th> <th>Date</th>  </tr>";
            $i = $starting_index;
            while ($row = $result->fetch_assoc()) {

                $date = date_create($row['news_date']);
                $mydate = date_format($date, "d/m/Y h:i a");
                $newsid = $row['news_id'];

                echo "<tr><td>$i</td>
                 <td>" . $row['news_title'] . "</td><td>" . $row['news_desc'] . "</td><td>" . $mydate . "</td>";
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
            echo '<a href = "index.php?pageno=' . $page . '" style = "text-decoration: none;" >&nbsp&nbsp' . $page . ' </a>';
        }
        echo " ( " . $pageno . " )";
        echo "</center>";
        echo "</div>";
        ?>

    </div>



</body>

</html>