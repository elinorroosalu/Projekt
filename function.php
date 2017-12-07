<?php
	require("../../config.php");
	$database = "if17_veebipood_EGJ";
	
	session_start();
	
	function logIn($username, $password){
		$notice = "";
        //$vale = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT ID, Username, Password FROM login WHERE Username = ?");
		$stmt->bind_param("s", $username);
		$stmt->bind_result($id, $usernameFromDb, $passwordFromDb);
		$stmt->execute();
		
		if($stmt->fetch()){
		    $hash = hash("sha512", $password);
			if($hash == $passwordFromDb){
				$notice = "Sisse logitud!";
				
				$_SESSION["ID"] = $id;
				$_SESSION["userName"] = $usernameFromDb;
				//$_SESSION["First_Name"] =$signupFirstnameFromDb;
				
				header("Location: market.php");
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
	
	function signUp($signupFirstName, $signupFamilyName, $signupUsername, $signupPassword, $signupBirthDate, $gender, $signupEmail ){
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO login (First_name, Last_name, Username, Password, Birthday, Gender, Email) VALUES (?, ?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("sssssis", $signupFirstName, $signupFamilyName, $signupUsername, $signupPassword, $signupBirthDate, $gender, $signupEmail);
		if ($stmt->execute()){
			 if(isset($_SESSION["ID"])){
		        header("Location: market.php");
		        exit();
		     }
		    echo "Õnnestus!";
			
			logIn($signupUsername, $signupPassword);
			header("Location: market.php");
		   
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