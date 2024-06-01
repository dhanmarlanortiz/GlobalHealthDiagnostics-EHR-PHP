<?php 
ob_start();
session_start();

require_once('connection.php');
include('header.php');
preventAccess([['role' => 2, 'redirect' => 'client/index.php']]);
include('navbar.php');

$professionals = getProfessional($conn);
createMainHeader("Healthcare Professionals", array("Home", "Healthcare Professionals"));


include('footer.php');

