<?php
session_start();
unset($_SESSION["sessionid"]);
unset($_SESSION["admin_email"]);
header("Location: login.php?logout=1");
?>