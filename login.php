<?php
	require("../vpconfig.php");
	require("function.php");
	
	//kui on juba sisse logitud
	if(isset($_SESSION["userId"])){
		header("Location: main.php");
		exit();
	}	
	
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
	
	if(isset($_POST["signupButton"])){
		
	
	//kontrollime, kas kirjutati eesnimi
	if (isset($_POST["signupFirstName"])){
		if (empty($_POST["signupFirstName"])){
			$notice ="Eesnimi on sisestamata!";
		} else {
			$signupFirstName = test_input($_POST["signupFirstName"]);
		}
	}
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset($_POST["signupFamilyName"])){
		if (empty ($_POST["signupFamilyName"])){
			$notice ="Perekonnanimi on sisestamata!";
		} else {
			$signupFamilyName = test_input($_POST["signupFamilyName"]);
		}
	}
	
	//kontrollime, kas kirjutati kasutajanimi
	if (isset($_POST["signupUsername"])){
		if (empty ($_POST["signupUsername"])){
			$notice ="Kasutajanimi on sisestamata!";
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
			$notice .= "Kuupäev ei vasta nõuetele!";
		}
	}
	
	//kontrollime, kas kirjutati kasutajanimeks email
	if (isset ($_POST["signupEmail"])){
		if (empty ($_POST["signupEmail"])){
			$notice ="E-mail on sisestamata!";
		} else {
			$signupEmail = test_input($_POST["signupEmail"]);
			
			$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
			$signupEmail = filter_var($signupEmail, FILTER_VALIDATE_EMAIL);
		}
	}
	
	if (isset ($_POST["signupPassword"])){
		if (empty ($_POST["signupPassword"])){
			$notice = "Salasõna on sisestamata!";
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
			$notice = "Sugu on määramata!";
	}
	
	//KIRJUTAN UUE KASUTAJA ANDMEBAASI
	if(empty($notice)){
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		
		//kutsun salvestamise funktsiooni
		signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupUsername, $signupPassword);
		
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