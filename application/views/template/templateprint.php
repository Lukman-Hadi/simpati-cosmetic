<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
	<meta name="author" content="Creative Tim">
	<title>SIMPATI - <?= $title ?></title>
	<!-- Favicon -->
	<link rel="icon" href="<?= base_url() . PATH_ASSETS ?>img/brand/favicon.png" type="image/png">
	<!-- Fonts -->
	<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"> -->
	<!-- Icons -->
	<!-- Page plugins -->
	<!-- Argon CSS -->
	<link rel="stylesheet" href="<?= base_url() . PATH_ASSETS ?>css/argon.css?v=1.1.0" type="text/css">

	<?php
	foreach ($css_files as $file) { ?>
		<link type="text/css" rel="stylesheet" href="<?php echo base_url() . $file; ?>" />
	<?php } ?>
	<script src="<?= base_url() . PATH_ASSETS ?>vendor/jquery/dist/jquery.min.js"></script>


</head>

<body>
	<!-- Sidenav -->
	<!-- Main content -->
	<div class="main-content" id="panel">
		<!-- Topnav -->
		<!-- Header -->
		<?php echo $contents ?>
	</div>
	<!-- Argon Scripts -->
	<!-- Core -->
	<!-- Optional JS -->
	<script src="<?= base_url() . PATH_ASSETS ?>vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url() . PATH_ASSETS ?>js/argon.js?v=1.1.0"></script>
</body>

</html>
