<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

$url = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
if(!isset($_SESSION["valid"])){
    header("location:" . $url . "/login.php");
    exit();
}
/* AUTHENTICATION - END */

header("location:" . $url . "/home.php");
exit();