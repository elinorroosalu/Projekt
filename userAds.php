<?php
	require("function.php");
	require("editmarketfunction.php");
	

	$notice = "";
	$page="";
	$limit = 20;

	//kui pole sisse logitud, liigume login lehele
	if(!isset($_SESSION["ID"])){
		header("Location: main.php");
		exit();
	}
	
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy(); //lõpetab sessiooni
		header("Location: main.php");
	}

	//$userAds = showAllThumbnails;


?>
<DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/modal.css">
	<link rel="stylesheet" type="text/css" href="style/general.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	<script type="text/javascript" src="javascript/modal.js" ></script>
</head>
<body class = "bg-info">
<div class="container-fluid text-white">
	<div class="row">
		<div class="col-sm-2">
		<br>
		<h3>Tere,<?php echo " " .$_SESSION["userName"]; ?></h3>
		<button type="button" class="btn btn-light"><a href="?logout=1">Logi välja</a></button><br><br>
		<button type="button" class="btn btn-light"><a href="main.php">Pealeht</a></button><br><br>
		<button type="button" class="btn btn-light"><a href="editmarket.php">Lisa uus kuulutus</a></button>
		</div>
		<div class="col-sm-8">
		<h1>EGJ VEEBIPOOD</h1>
		<h2>Sinu kuulutused</h2>

		<div id="myModal" class="modal">
		<!-- The Close Button -->
		<span class="close">&times;</span>
		<img class="modal-content" src="photos/" alt="Heading" id="modalImage">
		<div id="caption"></div>
	</div>
	
	<div id="allThumbnails">	
			
		<div class="container">
			<?php showAllThumbnails(); ?>
				<div class="middle">
					<a href="edituserAd" class="text">Muuda kuulutust</a>
				</div>
		</div>
	</div>
</div>
	
</body>
</html>