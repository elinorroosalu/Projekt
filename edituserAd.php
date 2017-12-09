<?php
require("../../config.php");
require("function.php");
require("editmarketfunction.php");
require("classes/photoClass.php");
$database = "if17_veebipood_EGJ";


    //kui pole sisse logitud, liigume login lehele
	if(!isset($_SESSION["ID"])){
		header("Location: main.php");
		exit();
	}
		
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy(); 
		header("Location: main.php");
	}
		
//muutuja
$notice = "";	
	
		// kui klõpsati uuendamise nuppu
		if(isset($_POST["adBtn"])){
			updateAd($_POST["ID"], test_input($_POST["Heading"]), test_input($_POST["Descript"]));
			//header("Location: ?id=" .$_POST["id"]); //Peale salvestamist jääb samale lehele
			header("Location: market.php"); //peale salvestamist läheb tagasi marketi lehele
			exit();
		}
		
		//Kas kustutatakse
		if(isset($_GET["deleted"])){
			deleteAd($_GET["ID"]);
			header("Location: market.php"); //peale salvestamist läheb tagasi marketi lehele
			exit();
		}
		
		//$ad = getSingAd($_GET["ID"]); //Kas siin on vaja????

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EGJ veebipood</title>
	<link rel="stylesheet" type="text/css" href="style/general.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
<script type="text/javascript" src="javascript/checkFileSize.js" defer></script>
</head>
<body class="bg-info">
<div align="center" class="container-fluid text-white">
	

    <br>
 	<button><a href="market.php">Pealeht</a></button>   
	<button><a href="?logout=1">Logi välja</a></button><br><br>
	
    <h2>Muuda toodet</h2><br>
	<form method="POST" action="edituserAd.php" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<input name="ID" type="hidden" value="<?php echo $_GET["ID"]; ?>">
		<label>Vali pilt: </label>
		<input type="file" name="fileToUpload" id="fileToUpload">
        <br>
	    <label>Kuulutuse pealkiri: </label>
	    <input name="Heading" type="text">
	    <br>
	    <label> Toote kirjeldus: </label>
	    <textarea name="Descript" rows="5" type="text"></textarea>
	    <br>
	    <input type="radio" name="privacy" value="1">
	    <label>&nbsp; Avalik &nbsp;</label>
	    <input type="radio" name="privacy" value="2">
	    <label>&nbsp; Registreeritud kasutajatele &nbsp;</label>
	    <br>
	    <input name="submit" type="submit" value="Salvesta kuulutus!" id="photoSubmit"><span id="fileSizeError"></span><span><?php echo $notice;?></span>
	</form>
	<p><a href="?ID=<?=$_GET["ID"];?>&delete=true">Kustuta see kuulutus</a>!</p>
	<hr>
	
</div>
<img src="<?php //echo $target_file; ?>" >
</body>
</html>