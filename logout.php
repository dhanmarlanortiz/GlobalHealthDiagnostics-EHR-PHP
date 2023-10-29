<?php
    session_start();
    unset($_SESSION['valid']);
    unset($_SESSION['timeout']);
    unset($_SESSION['username']);
    unset($_SESSION['role']);
    unset($_SESSION['email']);
    unset($_SESSION['isActive']);

    $url = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
    header('Refresh: 2; URL = ' . $url . "/login.php");
?>