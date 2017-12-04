<?php

require("function.php");
require("../../config.php");
		//kui pole sisse logitud, liigume login lehele
		/*if(!isset($_SESSION["userId"])){
			header(
		}*/
		
		


	//muutujad
$signupFirstNameFromDb = "";
$loginUserName = "";
$notice = "";
$id="";		
	
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

	
	<?php echo "Tere ". $signupFirstNameFromDb;?>
	</form>
	
	
</body>
</html>
	