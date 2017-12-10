<?php
	require("function.php");

	$notice = "";
	$ads = "";

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

	
	$ads=readUserAds()

?>
<DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/general.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>
<body class = "bg-info">
	<div class="container-fluid text-white">
	<div class="row">
	<div class="col-sm-2">
	<br>
	<h3>Tere<?php echo " " .$_SESSION["userName"]; ?></h3>
	<button><a href="?logout=1">Logi välja</a></button><br><br>
	<button><a href="main.php">Pealeht</a></button><br><br>
	</div>
	<div class="col-sm-8">
	<h1>EGJ VEEBIPOOD</h1>
	<h2>Kuulutused</h2>
		<?php echo $ads; ?>
	</div>
	</div>
	</div>
</body>
</html>