<?php
	require("../../config.php");
	$database = "if17_veebipood_EGJ";
	$target_dir = "photos/";
    $thumbs_dir = "thumbs/";
	
	session_start();
	
	function logIn($username, $password){
		$notice = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT ID, First_Name, Username, Password FROM login WHERE Username = ?");
		$stmt->bind_param("s", $username);
		$stmt->bind_result($id, $firstnameFromDb, $usernameFromDb, $passwordFromDb);
		$stmt->execute();
		
		if($stmt->fetch()){
		    $hash = hash("sha512", $password);
			if($hash == $passwordFromDb){
				$notice = "Sisse logitud!";
				
				$_SESSION["ID"] = $id;
				$_SESSION["First_Name"] =$firstnameFromDb;
				$_SESSION["userName"] = $usernameFromDb;
				
				
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

	function readUserAds(){
		$ads = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//$stmt = $mysqli->prepare("SELECT idea, ideacolor FROM vp2userideas");//absoluutselt kõigi mõtted
		//$stmt = $mysqli->prepare("SELECT idea, ideacolor FROM vp2userideas WHERE userid = ?");
		$stmt = $mysqli->prepare("SELECT ID, Heading, Descript FROM market WHERE ID = ? AND deleted IS NULL ORDER BY ID DESC");
		$stmt->bind_param("i", $_SESSION["ID"]);
		
		$stmt->bind_result($ID, $Heading, $Descript);
		$stmt->execute();
		while ($stmt->fetch()){
			/*$ads .= '<p style="background-color: ' .$Heading .'">' .$Descript .' | <a href="edituserad.php?id=' .$id .'">Toimeta</a>' ."</p> \n"; */
			//lisame lingi:  | <a href="edituseridea.php?id=6">Toimeta</a>
		}
		
		$stmt->close();
		$mysqli->close();
		return $ads;
	}
	function latestPicture($privacy){
		//$privacy = 1;
		$html = "<p>Värskeid avalikke pilte pole! Vabandame!</p>";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM photos WHERE id=(SELECT MAX(id) FROM photos WHERE  privacy<=?)");
		echo $mysqli->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($filename, $thumbnail, $alt);
		$stmt->execute();
		echo $stmt->error;
		if($stmt->fetch()){
			
			$html = '<img src="' .$GLOBALS["photo_dir"] .$filename .'" alt="' .$alt .'" class="rounded">';
		}
		$stmt->close();
		$mysqli->close();
		return $html;
	}
	
	function showAllThumbnails(){
		$html = "<p>Te pole ise ühtki pilti üles laadinud!</p>";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM photos WHERE userid = ?");
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($filename, $thumbnail, $alt);
		$stmt->execute();
		//kõik pisipildid
		if($stmt->fetch()){
			$html = '<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}
		while ($stmt->fetch()){
			$html .= "\t" .'<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}
		
		$stmt->close();
		$mysqli->close();
		echo $html;
	}
	
	function showThumbnailsPage($page, $limit){
		$skip = ($page - 1) * $limit;
		$html = "<p>Te pole ise ühtki pilti üles laadinud!</p>";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM vpphotos WHERE userid = ?");
		$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM photos WHERE userid = ? ORDER BY id DESC LIMIT " .$skip ."," .$limit);
		$stmt->bind_param("i", $_SESSION["ID"]);
		$stmt->bind_result($filename, $thumbnail, $alt);
		
		$stmt->execute();
		
		
		
		//kõik pisipildid
		if($stmt->fetch()){
			$html = '<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}
		while ($stmt->fetch()){
			$html .= "\t" .'<img src="' .$GLOBALS["thumb_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}
		
		$stmt->close();
		$mysqli->close();
		echo $html;
	}
	
	function showSharedThumbnailsPage($page, $limit){
		$skip = ($page - 1) * $limit;
		$html = "<p>Te pole ise ühtki pilti üles laadinud!</p>";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		//$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM vpphotos WHERE userid = ?");
		//$stmt = $mysqli->prepare("SELECT filename, thumbnail, alt FROM vpphotos WHERE privacy < ? ORDER BY id DESC LIMIT " .$skip ."," .$limit);
		$stmt = $mysqli->prepare("SELECT First_Name, Last_Name, filename, thumbnail, alt FROM photos, login WHERE photos.userid = login.ID AND photos.privacy < ? ORDER BY photos.id DESC LIMIT " .$skip ."," .$limit);
		$privacyVal = 2;
		$stmt->bind_param("i", $privacyVal);
		//$stmt->bind_result($filename, $thumbnail, $alt);
		$stmt->bind_result($firstname, $lastname, $filename, $thumbnail, $alt);
		
		$stmt->execute();
				
		//kõik pisipildid
		/*if($stmt->fetch()){
			$html = '<img src="' .$GLOBALS["thumbs_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs">' ."\n";
		}*/
		$html = "\n";
		while ($stmt->fetch()){
			$html .= "\t" .'<div class="thumbGallery">' ."\n";
			$html .= "\t \t" .'<img src="' .$GLOBALS["thumbs_dir"] .$thumbnail .'" alt="' .$alt .'" id="' .$filename .'" class="thumbs" title="' .$firstname ." " .$lastname .'">' ."\n";
			$html .= "\t \t <p>" .$firstname ." " .$lastname ."</p> \n";
			$html .= "\t </div> \n";
		}
		
		$stmt->close();
		$mysqli->close();
		echo $html;
	}
	
	function findNumberOfImages(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT COUNT(*) FROM `photos` WHERE userid = ?");
		$stmt->bind_param("i", $_SESSION["ID"]);
		$stmt->bind_result($imageCount);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		$mysqli->close();
		return $imageCount;
	}
	
	function findNumberOfSharedImages(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT COUNT(*) FROM `photos` WHERE privacy < ?");
		$privacyVal = 3;
		$stmt->bind_param("i", $privacyVal);
		$stmt->bind_result($imageCount);
		$stmt->execute();
		$stmt->fetch();
		$stmt->close();
		$mysqli->close();
		return $imageCount;
	}
	
	function addPhotoData($filename, $thumbname, $alt, $privacy){
		//echo $GLOBALS["serverHost"];
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO photos (userid, filename, thumbnail, alt, privacy) VALUES (?, ?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("isssi", $_SESSION["ID"], $filename, $thumbname, $alt, $privacy);
		//$stmt->execute();
		if ($stmt->execute()){
			$GLOBALS["notice"] .= "Foto andmete lisamine andmebaasi õnnestus! ";
		} else {
			$GLOBALS["notice"] .= "Foto andmete lisamine andmebaasi ebaõnnestus! ";
		}
		$stmt->close();
		$mysqli->close();
	}
?>