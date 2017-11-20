<?php
	require("../../vpconfig.php");
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
		$stmt = $mysqli->prepare("INSERT INTO login (First_name, Last_name, Username, Birthday, Gender, Email, Password) VALUES (?, ?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("sssssis", $signupFirstName, $signupFamilyName, $signupUsername, $signupPassword, $signupBirthDate, $gender, $signupEmail);
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
	
?>