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

if (isset($_GET["operation"])) {
    $resource_id = $_GET["resource_id"];
    $operation = $_GET["operation"];
    if ($operation == "delete") {
        $sqldelete = "DELETE FROM `tbl_resources` WHERE resource_id =  '$resource_id' ";
        if ($conn->query($sqldelete) === TRUE) {

            echo "<script> alert(' Success')</script>";
            echo "<script> window.location.replace('resources.php')</script>";
        } else {
            echo "<script> alert(' Failed')</script>";
        }
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
        <a href="#" class="w3-bar-item w3-button w3-mobile">Support</a>
        <a href="membership.php" class="w3-bar-item w3-button w3-mobile">Membership</a>
        <a href="profile.php" class="w3-bar-item w3-button w3-mobile">Profile</a>
        <a href="#" class="w3-bar-item w3-button w3-mobile">Logout</a>
        <a class="w3-right w3-button" onclick="document.getElementById('id01').style.display='block'" class="w3-bar-item w3-button w3-mobile">Add new resources</a>
    </div>

    <div class="w3-container w3-padding-large" style="height : 750px">
        <?php
        if ($result->num_rows > 0) {
            echo "<table class = 'w3-table w3-striped'>";
            echo "<tr> <th>no</th> <th>resources id</th> <th>resources title</th> <th>description</th> <th>category</th>  <th>resource reg date</th>  <th>operations</th>  </tr>";
            $i = $starting_index;
            while ($row = $result->fetch_assoc()) {


                $resource_id = $row['resource_id'];
                $date = date_create($row['resource_date']);
                $mydate = date_format($date, "d/m/Y h:i a");
                //table

                echo "<tr><td>$i</td>
                <td> " . $row['resource_id'] . "</td> <td> " . $row['resource_title'] . "</td>   <td>" . $row['resource_description'] . "</td>  <td>" . $row['resource_category'] . "</td>  <td>  $mydate   </td>";
                echo "<td> <a href = 'resources.php?operation=delete&resource_id=$resource_id' onclick=\"return confirm('Are you sure?')\">Delete </a> &nbsp <a href = '../resources/$resource_id.pdf'> Link </a></td> </tr>";
                $i++;
            }
            echo "</table>";
        }
        ?>

</body>

<div id="id01" class="w3-modal">
    <div class="w3-modal-content">
        <div class="w3-container w3-padding-large">
            <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-display-topright">&times;</span>
            <h3>RESOURCES</h3>
        </div>

        <div class="w3-container w3-padding">
            <h2>Add Resource</h2>
            <form action="resources.php" method="post" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input class="w3-input" type="text" id="title" name="resource_title" required>

                <label for="description">Description:</label>
                <textarea class="w3-input" id="description" name="resource_description" rows="4" required></textarea>

                <label for="category">Category:</label>
                <select class="w3-select" id="category" name="resource_category" required>
                    <option value="" disabled selected>Select Category</option>
                    <option value="Articles">Articles</option>
                    <option value="Videos">Videos</option>
                    <option value="Downloads">Downloads</option>
                </select>
                <label> Upload File :
                    <input class="w3-button w3-block w3-round" name="fileToUpload" type="file" id="idtitle" required accept="application/pdf">
                </label>

                <label for="date">Date:</label>
                <input class="w3-input" type="date" id="date" name="resource_date" required>

                <button class="w3-button w3-blue w3-margin-top" type="submit" name="submit" >Submit</button>
            </form>
        </div>




    </div>
</div>


</form>
</div>
</div>
</div>