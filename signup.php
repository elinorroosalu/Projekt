<?php
	require("../../config.php");
	require("function.php");
	
	/*kui on juba sisse logitud
	if(isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}	*/
	
	
	$signupFirstName = "";
	$signupFamilyName = "";
	$signupEmail = "";
	$signupUsername = "";
	$gender = "";
	$signupPassword = "";
	$signupBirthDay = null;
	$signupBirthMonth = null;
	$signupBirthYear = null;
	$signupBirthDate = null;
	$notice = "";
	
	$signupFirstNameError = "";
	$signupFamilyNameError = "";
	$signupUserNameError = "";
	$signupBirthDayError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$signupGenderError = "";
	
	if(isset($_POST["signupButton"])){
		
	
		//kontrollime, kas kirjutati eesnimi
		if (isset($_POST["signupFirstName"])){
			if (empty($_POST["signupFirstName"])){
				$signupFirstNameError ="Eesnimi on sisestamata!";
			} else {
				$signupFirstName = test_input($_POST["signupFirstName"]);
			}
		}
		
		//kontrollime, kas kirjutati perekonnanimi
		if (isset($_POST["signupFamilyName"])){
			if (empty ($_POST["signupFamilyName"])){
				$signupFamilyNameError ="Perekonnanimi on sisestamata!";
			} else {
				$signupFamilyName = test_input($_POST["signupFamilyName"]);
			}
		}
		
		//kontrollime, kas kirjutati kasutajanimi
		if (isset($_POST["signupUsername"])){
			if (empty ($_POST["signupUsername"])){
				$signupUserNameError ="Kasutajanimi on sisestamata!";
			} else {
				$signupUsername = test_input($_POST["signupUsername"]);
			}
		}
		
		if (isset ($_POST["signupBirthDay"])){
			$signupBirthDay = $_POST["signupBirthDay"];
		}
		
		//kas sünnikuu on valitud
		if( isset($_POST["signupBirthMonth"])){
			$signupBirthMonth = intval($_POST["signupBirthMonth"]);
		}
		
		if (isset ($_POST["signupBirthYear"])){
			$signupBirthYear = $_POST["signupBirthYear"];
		}
		
		if (isset ($_POST["signupBirthDay"]) and isset($_POST["signupBirthMonth"]) and isset ($_POST["signupBirthYear"])){
			//kontrollin kuupäeva valiidsust
			if(checkdate(intval($_POST["signupBirthMonth"]), intval($_POST["signupBirthDay"]), intval($_POST["signupBirthYear"]))){
				$birthDate = date_create(intval($_POST["signupBirthMonth"]) ."/" .intval($_POST["signupBirthDay"]) ."/" .intval($_POST["signupBirthYear"]));
				$signupBirthDate = date_format($birthDate, "Y-m-d");
				//echo $signupBirthDay;
			} else {
				$signupBirthDayError = "Kuupäev ei vasta nõuetele!";
			}
		}
		
		//kontrollime, kas kirjutati kasutajanimeks email
		if (isset ($_POST["signupEmail"])){
			if (empty ($_POST["signupEmail"])){
				$signupEmailError ="E-mail on sisestamata!";
			} else {
				$signupEmail = test_input($_POST["signupEmail"]);
				
				$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
				$signupEmail = filter_var($signupEmail, FILTER_VALIDATE_EMAIL);
			}
		}
		
		if (isset ($_POST["signupPassword"])){
			if (empty ($_POST["signupPassword"])){
				$signupPasswordError = "Salasõna on sisestamata!";
			} else {
				//polnud tühi
				if (strlen($_POST["signupPassword"]) < 8){
					$notice = "Liiga lühike salasõna, sisesta vähemalt 8 tähemärki!";
				}
			}
		}
		
		if (isset($_POST["gender"]) && !empty($_POST["gender"])){ //kui on määratud ja pole tühi
				$gender = intval($_POST["gender"]);
			} else {
				$signupGenderError = "Sugu on määramata!";
		}
		
		//KIRJUTAN UUE KASUTAJA ANDMEBAASI
		if(empty($signupFirstNameError) and empty($signupFamilyNameError) and empty($signupUserNameError) and empty($signupBirthDayError) and empty($signupEmailError) and empty($signupPasswordError) and empty($signupGenderError)){
			$signupPassword = hash("sha512", $_POST["signupPassword"]);
			
			//kutsun salvestamise funktsiooni
			signUp($signupFirstName, $signupFamilyName, $signupUsername, $signupPassword, $signupBirthDate, $gender, $signupEmail);
			
		}
	
	}

	
	//Tekitame kuupäeva valiku
	$signupDaySelectHTML = "";
	$signupDaySelectHTML .= '<select name="signupBirthDay">' ."\n";
	$signupDaySelectHTML .= '<option value="" selected disabled>päev</option>' ."\n";
	for ($i = 1; $i < 32; $i ++){
		if($i == $signupBirthDay){
			$signupDaySelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
			} else {
				$signupDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ." \n";
			}
		
	}
	$signupDaySelectHTML.= "</select> \n";

	//Tekitame sünnikuu valiku
	$monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$signupMonthSelectHTML = "";
	$signupMonthSelectHTML .= '<select name="signupBirthMonth">' ."\n";
	$signupMonthSelectHTML .= '<option value="" selected disabled>kuu</option>' ."\n";
	foreach($monthNamesEt as $key=>$month){
		if ($key + 1 === $signupBirthMonth){
			$signupMonthSelectHTML .= '<option value="' .($key + 1) .'" selected>' .$month ."</option> \n";
			} else {			
				$signupMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month ."</option> \n";	
			}	
	}	
	$signupMonthSelectHTML .= "</select> \n";
	
	//Tekitame aasta valiku
	$signupYearSelectHTML = "";
	$signupYearSelectHTML .= '<select name="signupBirthYear">' ."\n";
	$signupYearSelectHTML .= '<option value="" selected disabled>aasta</option>' ."\n";
	$yearNow = date("Y");
	for ($i = $yearNow; $i > 1900; $i --){
		if($i == $signupBirthYear){
			$signupYearSelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
			} else {
				$signupYearSelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
			}
		
	}
	$signupYearSelectHTML.= "</select> \n";	
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
	<?php //echo $_SESSION["firstname"] ." " .$_SESSION["lastname"]; ?>
	</title>

	<link rel="stylesheet" type="text/css" href="style/general.css">
</head>
<body>
	<p class="center">Teretulemast meie suurepärasesse poodi!</p>
	
<hr>


	<h2 class="center">Registreeri end kasutajaks</h2>
	<hr><br><br>
	<div class="centerOnPage">
	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Eesnimi </label>
		<input name="signupFirstName" type="text" value="<?php echo $signupFirstName; ?>">
		<br><span><?php echo $signupFirstNameError; ?></span>
		<br><br>
		<label>Perekonnanimi </label>
		<input name="signupFamilyName" type="text" value="<?php echo $signupFamilyName; ?>">
		<br><span><?php echo $signupFamilyNameError; ?></span>
		<br><br>
		<label>Kasutajanimi </label>
		<input name="signupUsername" type="username">
		<br><span><?php echo $signupUserNameError; ?></span>
		<br><br>
		<label>Salasõna </label>
		<input name="signupPassword" type="password">
		<br><span><?php echo $signupPasswordError; ?></span>
		<br><br>
		<label>Teie sünnikuupäev </label>
		<?php echo $signupDaySelectHTML. $signupMonthSelectHTML. $signupYearSelectHTML ; ?>
		<br><span><?php echo $signupBirthDayError; ?></span>
		<br><br>
		<label>Sugu</label>
		<input type="radio" name="gender" value="1" <?php if ($gender == '1') {echo 'checked';} ?>><label>Mees</label> <!-- Kõik läbi POST'i on string!!! -->
		<input type="radio" name="gender" value="2" <?php if ($gender == '2') {echo 'checked';} ?>><label>Naine</label>
		<br><span><?php echo $signupGenderError; ?></span><br><br>
		
		<label>E-post</label>
		<input name="signupEmail" type="email" value="<?php echo $signupEmail; ?>">
		<br><span><?php echo $signupEmailError; ?></span>
		<br><br><br><br>
		

		
		<input name="signupButton" type="submit" value="Loo kasutaja">
	</form>
	</div>
</body>

</html>