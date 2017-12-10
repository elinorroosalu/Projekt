<?php
	require("function.php");
	
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
	
	$firstnameFromDb="";

	
	//piltide lehekülgede kontroll
	if(!isset($imageCount)){
		$imageCount = findNumberOfSharedImages();
	}
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		header("Location: ?page=1");
	}
	
	if($imageCount <= ($_GET["page"] - 1) * $limit){
		if($imageCount == 0){
			//header("Location: ?page=1");//tekitas edasisuunaiste tsükli
		} else {
			header("Location: ?page=" .ceil($imageCount / $limit));
		}
	}
	//require("header.php");
?>

	<link rel="stylesheet" type="text/css" href="style/modal.css">
	<link rel="stylesheet" type="text/css" href="style/general.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	<script type="text/javascript" src="javascript/modal.js" defer></script>
</head>
<body class="bg-info">
	<div class="container-fluid text-white">
	<?php
		//require("top_part.php");
	?>
	<div class="row">
	<div class="col-sm-2">
	<br>
	<h3>Tere<?php echo " " .$_SESSION["userName"]; ?></h3>
	<button><a href="?logout=1">Logi välja</a></button><br><br>
	<button><a href="main.php">Pealeht</a></button><br><br>
	<button><a href="editmarket.php">Lisa uus kuulutus</a></button>
	</div>
	<div class="col-sm-8">
	<h2>Kõik kuulutused</h2>
	
	<!-- The Modal W3schools eeskujul-->
	<div id="myModal" class="modal">
		<!-- The Close Button -->
		<span class="close">&times;</span>
		<!-- Modal Content (The Image) -->
		<img class="modal-content" src="../../graphics/hmv_safe.jpg" alt="" id="modalImage">
		<!-- Modal Caption (Image Text) -->
		<div id="caption"></div>
	</div>
	
	<div id="allThumbnails">
	<table class="pageLinks">
	<tr>
	<td class="half leftLink">
	<?php
		if($_GET["page"] > 1){
			echo '<a href="?page=' .($_GET["page"] - 1) .'">Eelmised kuulutused</a>';
		}
		
	?>
	</td>
	<td class="half rightLink">
	<?php
		if($imageCount > $_GET["page"] * $limit){
			echo '<a href="?page=' .($_GET["page"] + 1) .'">Järgmised kuulutused</a>';
		}
		
	?>
	</td>
	</tr>
	</table>
	<?php
		showSharedThumbnailsPage($_GET["page"], $limit);
	?>
	</div>
	</div>
	</div>
	<?php
		//require("footer.php");
	?>