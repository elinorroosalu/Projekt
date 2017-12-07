<?php
	require("../../config.php");
	$database = "if17_veebipood_EGJ";
	
	function getSingleAd($editId){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT Heading, Descript FROM market WHERE ID=?");
		$stmt->bind_param("i", $editID);
		$stmt->bind_result($Heading, $Descript);
		$stmt->execute();
		$adObject = new Stdclass();
		if($stmt->fetch()){
			$adObject->text = $Heading;
			$adObject->text = $Descript;
		}
		
		$stmt->close();
		$mysqli->close();
		return $ideaObject;
		
	}
	
	function updateIdea($ID, $Heading, $Descript){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE market SET Heading=?, Descriptr=? WHERE ID=?");
		$stmt->bind_param("iss", $ID, $Heading, $Descript);
		if($stmt->execute()){
			echo "Õnnestus";
		} else {
			echo "Tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$mysqli->close();
	}
	
	function deleteIdea($id){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE market SET deleted=NOW() WHERE id=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		
		$stmt->close();
		$mysqli->close();
	}
	
?>