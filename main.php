<?php

		require("function.php");
		require("../../config.php");
		//kui pole sisse logitud, liigume login lehele
	/*	if(!isset($_SESSION["userId"])){
			header("Location: main.php");
			exit();
		}*/
		
		/*//väljalogimine
		if(isset($_GET["logout"])){
			session_destroy(); //lõpetab sesiooni
			header("Location: main.php");
		}
		*/
					
	//muutujad
	$signupFirstNameFromDb = "";
	
$database = "if17_veebipood_EGJ";   
	
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		if ($stmt = $mysqli->prepare("SELECT First_Name FROM login WHERE id=".$_SESSION["userId"])){
            $stmt->execute();
		    $stmt->bind_result($signupFirstNameFromDb);
            while ($stmt -> fetch()){
		        $signupFirstNameFromDb;
		        }
		    
		
		    $stmt->close();
        }
		$mysqli->close();

	
	$picDir = "../../pics/";
	$picFiles = [];
	$picFileTypes = ["jpg", "jpeg", "png", "gif"];
	

	
	$allFiles = array_slice(scandir($picDir), 2);
	foreach ($allFiles as $file) {
		$fileType = pathinfo($file, PATHINFO_EXTENSION);
		if (in_array ($fileType, $picFileTypes) == true){
			array_push($picFiles, $file);
		}
	}//foreach lõppeb
	
	//var_dump($allFiles);  näitab veebilehel 
	//$picFiles = array_slice($allFiles, 2);
	//var_dump($picFiles);
	$picFileCount = count($picFiles);
	$picNumber = mt_rand(0, $picFileCount -1);
	$picFile = $picFiles[$picNumber];
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<h2>Logi sisse.</h2>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		
		<input name="UserName" placeholder="Kasutajanimi" type="text" >
		<span><?php echo $notice; ?></span>
		
		<input name="loginPassword" placeholder="Salasõna" type="password">
		<br><br>
		<input name="loginButton" type="submit" value="Logi sisse"> 
		<span><?php echo $notice; ?></span>
	</form>
</body>
</html>
	