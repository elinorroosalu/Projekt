<?php
	require("../../config.php");
	$database = "if17_veebipood_EGJ";
	
	session_start();
	
	function signIn($username, $password){
		$notice = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT ID, Username, Password FROM login WHERE Username = ?");
		$stmt->bind_param("s", $username);
		$stmt->bind_result($id, $usernameFromDb, $passwordFromDb);
		$stmt->execute();
		
		if($stmt->fetch()){
			if($password == $passwordFromDb){
				$notice = "Sisse logitud!";
				
				$_SESSION["userId"] = $id;
				$_SESSION["userName"] = $usernameFromDb;
				
				header("Location: main.php");
				exit();
				
			} else {
				$notice = "Vale salasõna!";
			}	
		} else {
			$notice = "Sellise kasutajanimega kasutajat pole!";
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}	
	
	function signUp($signupFirstName, $signupFamilyName, $signupUsername, $signupBirthDate, $gender, $signupEmail, $signupPassword){
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO login (First_Name, Last_Name, Username, Birthday, Gender, Email, Password) VALUES (?, ?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("ssssiss", $signupFirstName, $signupFamilyName, $signupUsername, $signupBirthDate, $gender, $signupEmail $signupPassword);
		if ($stmt->execute()){
			echo "Õnnestus!";
		} else {
			echo "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
	}
	
	function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;	
	}	
	
	//Kirjelduse salvestamine
	function saveDescript($heading, $descript){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO market (UserID, Heading, Descript) VALUES(?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("iss", $_SESSION["UserID"], $heading, $descript);
		if($stmt->execute()){
			$notice = "Kuulutus on lisatud!";
		} else {
			$notice = "Kuulutuse lisamisel tekkis tõrge: " .$stmt->error;
		}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
	}
	
?>