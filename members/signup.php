<?php
//fullname=Adam&phonenumber=01156900325&membership=full&email=m.aqielakhtar%40gmail.com&password=abc123&confirm_password=abc123&submit=Submit
if (isset($_POST["submit"])) {
    
    include "../dbconnect.php";
    $fullname = $_POST['fullname'];
    $phonenumber = $_POST['phonenumber'];
    $membership = $_POST['membership'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    $sqlinsert = "INSERT INTO `tbl_members`( `member_fullname`, `member_phonenumber`, `member_membership`, `member_email`, `member_password`) VALUES ('$fullname','$phonenumber','$membership','$email','$password')";

    if ($conn->query($sqlinsert) === TRUE) {
        $last_id = $conn->insert_id;
        if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
            uploadFile($last_id);
        }
        echo "<script> alert('Registeration Success')</script>";
    } else {
        echo "<script> alert('Registeration Failed')</script>";
    }

    $conn->close();
}

function uploadFile($cvname)
{
    $target_dir = "files/";
    $target_file = $target_dir . $cvname . ".pdf";
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
}


?>

<html>

<head>
    <title>SIGNUP</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../style.css">
    <script>
        var check = function() {
            if (document.getElementById('password').value ==
                document.getElementById('confirm_password').value) {
                document.getElementById('message').style.color = 'green';
                document.getElementById('message').innerHTML = 'matching';
            } else {
                document.getElementById('message').style.color = 'red';
                document.getElementById('message').innerHTML = 'not matching';
            }
        }
    </script>
</head>

<body>



    <div class="header">
        <h>Sign up</h>
    </div><br>
    <div class="w3-container w3-card" style=" margin:auto ; max-width:600px ">
        <div class="w3-container w3-padding-large" style=" margin:auto ; max-width:600px ">
            <h3>SIGNUP FORM</h3>
            <form action="signup.php" method="post" enctype="multipart/form-data"> 
                <label>Full name :
                    <input class="w3-input w3-border w3-round" type="text" name="fullname" id="idname" placeholder="Please enter your full name" required><br>
                </label>
                <label> Phone number :
                    <input class="w3-input w3-border w3-round" type="text" name="phonenumber" id="idphone" placeholder="Please enter your phone number" required><br>
                </label>

                <label for="membership">Choose membership:<br><br>

                    <select class="w3-input" name="membership" id="idmembership">
                        <option value="full">Full Membership</option>
                        <option value="student">Student Membership</option>
                        <option value="associate">Associate Membership</option>
                    </select>
                </label><br>

                <label> Upload CV :
                    <input class="w3-button w3-block w3-round" name="fileToUpload" type="file" id="idtitle" required accept="application/pdf">
                </label>


                <label> Email :
                    <input class="w3-input w3-border w3-round" type="email" name="email" id="idemail" placeholder="Please enter your email address" required><br>
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
    <div class="w3-container w3-padding-large" style=" margin:auto ; max-width:600px ">
        <p><a href="login.php">Go back to Login Page</p>
    </div>
</body>

</html>