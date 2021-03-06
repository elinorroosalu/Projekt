<?php
require("../../config.php");
require("function.php");
require("editmarketfunction.php");
require("classes/photoClass.php");
$database = "if17_veebipood_EGJ";

		
	//kui pole sisse logitud, liigume login lehele
	if(!isset($_SESSION["ID"])){
		header("Location: main.php");
		exit();
	}
		
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy(); 
		header("Location: main.php");
	}
	

	
//Fotolaadimise algus
$target_dir = "photos/";
$target_file = "";
$uploadOk = 1;
$imageFileType = "";
//pathinfo($target_file,PATHINFO_EXTENSION)
$notice = "";
$thumbs_dir = "thumbs/";
$thumb_file = "";
$thumbsize = 100;
$maxWidth = 600;
$maxHeight = 400;
$marginVer = 10;
$marginHor = 10; 


//Kas vajutati üleslaadimise nuppu (Kontrollib, kas pilt on päris või mitte)
if(isset($_POST["submit"])) {
	
        if(!empty($_FILES["fileToUpload"]["name"])){
        
            //$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            //filename- ainult nimi ilma jpg jne
            //microtime-mikrosekundites, korruta 10tuhandega, saad komadest lahti
            $imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
            $timestamp = microtime(1) *10000;
            //$target_file = $target_dir . pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" . $timestamp ."." .$imageFileType;
            //$target_file = $target_dir . "hmv_" . $timestamp ."." .$imageFileType;
			$target_file = "egj_" .$timestamp ."." .$imageFileType;
			$thumb_file = "egj_" .$timestamp .".jpg";
            //Thumbnailid
            /*$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]))["extension"]);
            $timestamp = microtime(1) *10000;
            //$target_file = $target_dir . pathinfo(basename($_FILES["fileToUpload"]["name"]))["filename"] ."_" . $timestamp ."." .$imageFileType;
            $thumb_file = $thumb_dir . "hmv_" . $timestamp ."." ."jpg";*/
        
        
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                $notice .= "Fail on pilt - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $notice .= "Üleslaetud fail ei ole pilt.";
                $uploadOk = 0;
            }
        
        
            //Kontrollib, kas fail on kaustas juba olemas
            if(file_exists($target_file)) {
                $notice .= "Samanimeline fail on juba olemas. ";
                $uploadOk = 0;
            }

            //Kontrollib faili suurust (max 2MB)
            /*if($_FILES["fileToUpload"]["size"] > 2000000) {
                $notice = "Pildi maht on liiga suur (lubatud kuni 2MB).";
                $uploadOk = 0;
            }*/

            //Kontrollib faili formaati
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $notice .= "Lubatud on ainult jpg, jpeg, png ja gif formaadis failid.";
                $upload = 0;
            }
			//Kas saab laadida
            //Kontrollib kas $uploadOk on errori käigus pandud 0-ks
            if($uploadOk == 0) {
                $notice .= " Faili ei laetud üles.";
        
            } else {
                //Kui kõik on korras laadi fail üles, teeb koopia tempfaili
                /*if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
                    $notice = "Fail ". basename($_FILES["fileToUpload"]["name"]). " on üles laetud.";
                } else {
                    $notice = "Vabandust, tekkis error.";
                }*/
            
				    //Kasutame klassi
				    $myPhoto = new Photoupload($_FILES["fileToUpload"]["tmp_name"], $imageFileType);
				    $myPhoto->readExif();
				    $myPhoto->resizeImage($maxWidth, $maxHeight);
				    //$myPhoto->addWatermark($marginHor, $marginVer);
				    //$myPhoto->addTextWatermark($myPhoto->exifToImage);
				    $myPhoto->addTextWatermark("EGJ");
				    $notice .= $myPhoto->savePhoto($target_dir, $target_file);
				    $notice .= $myPhoto->createThumbnail($thumbs_dir, $thumb_file, $thumbsize, $thumbsize);
				    /*if($notice =="true"){
					    $notice = "Pilt laeti üles";
				    } else {
					    $notice = "Pilti ei laetud üles";
				    }*/
				    //$myPhoto->saveOriginal(kataloog, failinimi);
				    $myPhoto->clearImages();
				    unset($myPhoto); //unustatakse kõik mis klassis töötasid
				    
				    //kas vajutati kirjelduse salvestamise nuppu
		            if(isset($_POST["submit"])){
			            if(isset($_POST["Heading"]) and isset($_POST["Descript"]) and !empty($_POST["Heading"]) and !empty($_POST["Descript"])){
				            //echo $_POST["ideaColor"];
				            $notice = saveAd($_POST["ID"], test_input($_POST["Heading"]), test_input($_POST["Descript"]));
			            }
		            }
				    
				    
				    //lisame andmebaasi
				    if(isset($_POST["Descript"]) and !empty($_POST["Descript"])){
					        $alt = $_POST["Descript"];
				    } else {
					        $alt = "Foto";
				    }
					
				    addPhotoData($target_file, $thumb_file, $alt, $_POST["privacy"]);
				    
              }
        } else {
            $notice = "Palun valige kõigepealt pildifail";
        }//kas faili nimi on olemas lõppeb
        
}//"Kas üles laadida" lõppeb



/*		// kui klõpsati uuendamise nuppu
		if(isset($_POST["adBtn"])){
			updateAd($_POST["id"], test_input($_POST["Heading"]), test_input($_POST["Descript"]));
			//header("Location: ?id=" .$_POST["id"]); //Peale salvestamist jääb samale lehele
			header("Location: market.php"); //peale salvestamist läheb tagasi marketi lehele
			exit();
		}
		
		//Kas kustutatakse
		if(isset($_GET["deleted"])){
			deleteAd($_GET["id"]);
			header("Location: market.php"); //peale salvestamist läheb tagasi marketi lehele
			exit();
		}*/
		
		//$ad = getSingAd($_GET["id"]); //Kas siin on vaja????



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EGJ veebipood</title>
	<link rel="stylesheet" type="text/css" href="style/general.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
<script type="text/javascript" src="javascript/checkFileSize.js" defer></script>
</head>
<body class="bg-info">
<div align="center" class="container-fluid text-white">
    <h2>Lisa uus toode</h2><br>
 	<button type="button" class="btn btn-light"><a href="market.php">Pealeht</a></button>   
	<button type="button" class="btn btn-light"><a href="?logout=1">Logi välja</a></button><br><br>


<form action="editmarket.php" method="POST" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input name="ID" type="hidden" value="<?php echo $_GET["ID"]; ?>">
	<label>Vali pilt: </label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <br>
	<label>Kuulutuse pealkiri: </label>
	<input name="Heading" type="text">
	<br>
	<label> Toote kirjeldus: </label>
	<textarea name="Descript" rows="5" type="text"></textarea>
	<br>
	<input type="radio" name="privacy" value="1">
	<label>&nbsp; Avalik &nbsp;</label>
	<input type="radio" name="privacy" value="2">
	<label>&nbsp; Registreeritud kasutajatele &nbsp;</label>
	<br>
	<input name="submit" type="submit" value="Salvesta kuulutus!" id="photoSubmit"><span id="fileSizeError"></span><span><?php echo $notice;?></span>
</form>

</div>
<img src="<?php //echo $target_file; ?>" >
</body>
</html>