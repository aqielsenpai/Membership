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
if (isset($_POST["submit"])) {
    $resource_title = $_POST["resource_title"];
    $resource_description = $_POST["resource_description"];
    $resource_category = $_POST["resource_category"];
    $resource_date = $_POST["resource_date"];
    $sqlinsertresource = "INSERT INTO `tbl_resources`( `resource_id`, `resource_title`, `resource_description`, `resource_category`, `resource_date`) VALUES ( ' $resource_id', ' $resource_title','$resource_description','$resource_category','$resource_date')";


    if ($conn->query($sqlinsertresource) === TRUE) {
        $last_id = $conn->insert_id;
        if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
            uploadFile($last_id); 
        }
        echo "<script> alert(' Success')</script>";
        echo "<script> window.location.replace('resources.php')</script>";
    } else {
        echo "<script> alert(' Failed')</script>";
    }
}




$sqlloadresources = "SELECT * FROM `tbl_resources` ";
$results_per_page = 10;
if (isset($_GET['pageno'])) {
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
} else {
    $pageno = 1;
    $page_first_result = ($pageno - 1) * $results_per_page;
}

$result = $conn->query($sqlloadresources);
$number_of_result = $result->num_rows;
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlloadresources = $sqlloadresources . " LIMIT $page_first_result , $results_per_page ";
$result = $conn->query($sqlloadresources);
$starting_index = ($pageno - 1) * $results_per_page + 1;

function uploadFile($resources)
{
    $target_dir = "../resources/";
    $target_file = $target_dir . $resources . ".pdf";
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
}


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
        <a href="#" class="w3-bar-item w3-button w3-mobile">Logout</a>
       
    </div>

    <div class="w3-container w3-padding-large" style="height : 750px">
        <?php
        if ($result->num_rows > 0) {
            echo "<table class = 'w3-table w3-striped'>";
            echo "<tr> <th>no</th> <th>resources id</th> <th>resources title</th> <th>description</th> <th>category</th>  <th>resource reg date</th>   </tr>";
            $i = $starting_index;
            while ($row = $result->fetch_assoc()) {


                $resource_id = $row['resource_id'];
                $date = date_create($row['resource_date']);
                $mydate = date_format($date, "d/m/Y h:i a");
                //table

                echo "<tr><td>$i</td>
                <td> " . $row['resource_id'] . "</td> <td> " . $row['resource_title'] . "</td>   <td>" . $row['resource_description'] . "</td>  <td>" . $row['resource_category'] . "</td>  <td>  $mydate   </td>";
                
                $i++;
            }
            echo "</table>";
        }
        ?>

</body>



