<?php
    require("../../config.php");
    require("function.php");

		//kui on sisse logitud, liigume market lehele
	if(isset($_SESSION["ID"])){
		header("Location: market.php");
		exit();
	}		
		
	//muutujad
$username = "";
$loginUserNameError = "";
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
		if (isset ($_POST["loginUserName"])){
			if (empty ($_POST["loginUserName"])){
				$loginUserNameError ="NB! Sisselogimiseks on vajalik kasutajanimi!";
			} else {
				$username = $_POST["loginUserName"];
				//echo "Sisse logitud";
		    }
	    }
	    
	    if(!empty($username) and !empty($_POST ["loginPassword"])){
		    //$hash = hash("sha512", $_POST["Password"]);
		    $notice = logIn($username, $_POST["loginPassword"]);
		    //echo "Sisse logitud";
	    }
	}//if loginButton



	
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style/general.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
</head>
<body class="bg-info">
<div align="center" class="container-fluid text-white">
<h1>EGJ VEEBIPOOD</h3>
<h3>Logi sisse.</h3>
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		
		<input name="loginUserName" placeholder="Kasutajanimi" type="text" value="<?php echo $username; ?>">
		
		<input name="loginPassword" placeholder="Salasõna" type="password"><span></span>
		<br><br>
		<input name="loginButton" type="submit" value="Logi sisse"> 
		<span><?php echo $notice; ?></span>
	</form>
	
<br><br>
<h4>Pole veel kasutajat?</h4>

<button><a href="signup.php">Tee uus kasutaja</a></button>

</div>	
</body>
</html>
	