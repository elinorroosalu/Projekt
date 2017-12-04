<?php

require("function.php");
require("../../config.php");
		//kui pole sisse logitud, liigume login lehele
		/*if(!isset($_SESSION["userId"])){
			header("Location: main.php");
			exit();
		}*/
	//muutujad
$signupFirstNameFromDb = "";
$loginUserName = "";
$notice = "";		
	
		/*//väljalogimine
		if(isset($_GET["logout"])){
			session_destroy(); //lõpetab sesiooni
			header("Location: main.php");
		}
		*/
		//alustame sessiooni


	if(isset($_POST["loginButton"])) {
	//kas on kasutajanimi sisestatud
		if (isset ($_POST["UserName"])){
			if (empty ($_POST["UserName"])){
				$notice ="NB! Sisselogimiseks on vajalik kasutajanimi!";
			} else {
				$loginUserName = $_POST["UserName"];
				echo "Sisse logitud";
		}
	}
	if(!empty($loginUserName) and !empty($_POST ["Password"])){
		$hash = hash("sha512", $_POST["Password"]);
		$notice = logIn($loginUserName, $hash);
	}
	}//if loginButton

	
	$database = "if17_veebipood_EGJ";  
		//$_SESSION["userId"] = $id;
//Tervitab kasutajat nimega	
		/*$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		if ($stmt = $mysqli->prepare("SELECT First_Name FROM login WHERE ID=".$_SESSION["userId"])){
            $stmt->execute();
		    $stmt->bind_result($signupFirstNameFromDb);
            while ($stmt -> fetch()){
		        $signupFirstNameFromDb;
		        }
		    
		    $stmt->close();
        }
		$mysqli->close();
*/
	
	/*$picDir = "../../pics/";
	$picFiles = [];
	$picFileTypes = ["jpg", "jpeg", "png", "gif"];
	*/

	
	/*$allFiles = array_slice(scandir($picDir), 2);
	foreach ($allFiles as $file) {
		$fileType = pathinfo($file, PATHINFO_EXTENSION);
		if (in_array ($fileType, $picFileTypes) == true){
			array_push($picFiles, $file);
		}
	}//foreach lõppeb */
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
<h3>Logi sisse.</h3>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		
		<input name="UserName" placeholder="Kasutajanimi" type="text" >
		<span><?php echo $notice; ?></span>
		
		<input name="loginPassword" placeholder="Salasõna" type="password">
		<br><br>
		<input name="loginButton" type="submit" value="Logi sisse"> 
		<span><?php echo $notice; ?></span>

	
	<?php echo "Tere ". $_SESSION["First_Name"];?>
	</form>
	
	
</body>
</html>
	