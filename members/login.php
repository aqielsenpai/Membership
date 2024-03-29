<?php

if (isset($_GET["logout"]) && $_GET["logout"] == 1) {
    echo "<script>alert('Logout success');</script>";
}
if (isset($_POST["submit"])) {
    include "../dbconnect.php";
    $email = $_POST['email'];
    $password = sha1($_POST['password']);
    $sqlLogin = "SELECT * FROM `tbl_members` WHERE member_email = '$email' AND member_password = '$password' ";
    $result = $conn->query($sqlLogin);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            session_start();
            $_SESSION["sessionid"] = session_id();
            $_SESSION["member_id"] = $row["member_id"];
            $_SESSION["member_email"] = $row["member_email"];
            $_SESSION["member_fullname"] = $row["member_fullname"];
            $_SESSION["member_phonenumber"] = $row["member_phonenumber"];
            //echo "id: " . $row["member_id"]. " - Name: " . $row["member_fullname"]. " " . $row["member_phonenumber"]. "<br>";
            echo "<script> alert('Login success')</script>";
            echo "<script> window.location.replace('index.php')</script>";
        }
    } else {
        echo "<script> alert('Login Failed')</script>";
    }
    $conn->close();
}




?>

<html>


<head>
    <title>LOGIN</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../style.css">
    <script type="text/javascript" src="../myscript.js"></script>



</head>

<body onload="loadPref()">

    <div class="header">
        <h>Login</h>
    </div><br>
    <div class="w3-container w3-card" style=" margin:auto ; max-width:600px ">
        <div class="w3-container w3-padding-large" style=" margin:auto ; max-width:600px ">
            <h3>LOGIN FORM</h3>
            <form method="post" action="login.php">
                <input class="w3-input w3-border w3-round" type="email" name="email" id="idemail" placeholder="Please enter your email address" required><br>
                <input class="w3-input w3-border w3-round" type="password" name="password" id="idpassword" placeholder="Please enter your password" required><br>
                <input class="w3-button w3-teal w3-round" type="submit" name="submit"><br>
                <p><input type="checkbox" onclick="savepref()" name="rememberme" id="idcheckbox"> Remember me</p>
            </form>
        </div>
    </div>
    <div class="w3-container w3-padding-large" style=" margin:auto ; max-width:600px ">
        <p><a href="signup.php">Click here to create new account</p>
    </div>

</body>

</html>