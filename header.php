<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require('globals.php');
	require ('functions/flash.php');
?>
<!doctype html>
<html class="light">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="color-scheme" content="light only">

	<title>Global Health Diagnostics</title>
	<link rel="shortcut icon" href="images/ghd-logo.png" type="image/x-icon">

	<?php 
	$serverName = $_SERVER['SERVER_NAME']; // or $_SERVER['HTTP_HOST']
	if ($serverName === 'localhost' || $serverName === '127.0.0.1') {
		echo '
		<script src="'. base_url(false) .'/js/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		<script src="'. base_url(false) .'/js/tailwindcss.js"></script>
		
		<link href="'. base_url(false) .'/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
		<link href="'. base_url(false) .'/css/daisyui.css" rel="stylesheet" type="text/css" />
		';
	} else {
		echo '
		<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
		<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
		
		<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
		<link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.3/dist/full.css" rel="stylesheet" type="text/css" />
		';
	}
	?>

	<link href="<?php base_url(); ?>/css/main.css" rel="stylesheet" type="text/css" />
</head>
<body class="">
	<div class="relative isolate bg-gray-50" style="min-height: calc(100vh - 52px);">
		<div class="absolute opacity-30 inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
			<div
				class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#15803d] to-[#facc15] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
				style="clipPath: 'polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)',"
			>
			</div>
		</div>
