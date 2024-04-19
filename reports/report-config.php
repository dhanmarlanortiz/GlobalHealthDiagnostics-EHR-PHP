<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
ob_start();
session_start();

$live = 'app.globalhealth-diagnostics.com';
$liveURL = "https://app.globalhealth-diagnostics.com";
$devURL = "http://localhost/globalhealth-php";
$baseURL = ($_SERVER['HTTP_HOST'] == $live) ? $liveURL : $devURL;

// Check if logged in
if(!isset($_SESSION["valid"])){
    header("location:" . $baseURL . "/login.php");
    exit();
}

// Check if ID is set
if (!isset($_GET['id'])) {
    header("location:" . $baseURL . "/page-not-found.php");
    exit();
}

include("../connection.php");
include("../globals.php");
require('../fpdf/fpdf.php');

$id = $_GET['id'];
$apeDetails = fetchApeDetailsById($conn, $id);
$orgDetails = fetchOrgDetailsById($conn, $apeDetails['organizationId']);

// If not admin, check if user org and patient org is the same
if( $_SESSION['role'] != 1 ) {
    if( $_SESSION['organizationId'] != $apeDetails['organizationId'] ) {
        header("location:" . $baseURL . "/page-not-found.php");
        exit();
    }
}