<?php 
/* AUTHENTICATION - START */
ob_start();
session_start();

if(!isset($_SESSION["valid"])){
	$url = ($_SERVER['HTTP_HOST'] == 'app.globalhealth-diagnostics.com') ? "https://app.globalhealth-diagnostics.com" : "http://localhost/globalhealth-php";
    header("location:" . $url . "/login.php");
    exit();
}
/* AUTHENTICATION - END */

include('header.php');
include('navbar.php'); 
?>
	
			<div class="mx-auto max-w-3xl py-32 sm:py-48 lg:py-56">
				<div class="hidden sm:mb-8 sm:flex sm:justify-center">
					<div class="relative rounded-full px-3 py-1 text-sm leading-6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
						Version 1.0
					</div>
				</div>
				<div class="text-center">
					<h1 class="text-4xl font-bold tracking-tight text-green-700 sm:text-6xl">Global Health Diagnostics</h1>
					<p class="mt-6 text-lg leading-8 text-gray-600">Electronic Health Record System</p>
				</div>
			</div>

			

<?php 
include('footer.php');
?>