<!doctype html>
<html class="h-full bg-gray-100">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Global Health Diagnostics</title>
	<link rel="shortcut icon" href="images/ghd-logo.png" type="image/x-icon">
	
	<script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>

	<link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.3/dist/full.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">

	<style>
		table {
			visibility: hidden;
		}

		table:before {
			content: "Generating result ...";
			visibility: visible;
			font-size: 14px;
		}

		table.dataTable {
			visibility: visible;
		}

		table.dataTable:before {
			content: none;
		}

		.dataTables_length select {
			padding-right: 20px;	
		}
	</style>

</head>
<body class="h-full">