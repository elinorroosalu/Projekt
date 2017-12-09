<?php 
//Klasside nimed alati suure algustähega

	class Photoupload {
		//properties ehk muutujad, methods ehk funktsioonid
		//public $testPublic; //väljaspool klassi kätte saadavad (mysqli on ka klass, sealne error on public muutuja)
		//public $testPrivate;
		//private $testPrivate; Kui ei näe põhjust väljapoole klassi näidata
		//proteced -sarnane privatile, kättesaadav laiendatud klasside peal
		
		private $tempName;
		private $imageFileType;
		private $myTempImage;
		private $myImage;
		public $exifToImage;

		
		function __construct($name, $type){
			//$this->testPublic = "Väga avalik muutuja";
			//$this->testPrivate = $x;
			$this->tempName = $name;
			$this->imageFileType = $type;
		}
		private function createImage(){
			//lähtudes failitüübist, loome pildiobjekti
            if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
                $this->myTempImage = imagecreatefromjpeg($this->tempName);	
            }
            if($this->imageFileType == "png"){
                $this->myTempImage = imagecreatefrompng($this->tempName);	
            }
            if($this->imageFileType == "gif"){
				$this->myTempImage = imagecreatefromgif($this->tempName);
            }
		}
		
		public function resizeImage($width, $height){
			$this->createImage();
			$imageWidth = imagesx($this->myTempImage);
            $imageHeight = imagesy($this->myTempImage);
            
            $sizeRatio = 1;
            if($imageWidth > $imageHeight){
                $sizeRatio = $imageWidth / $width;
            } else {
                $sizeRatio = $imageHeight / $height;
            }
            //Funktsioon piltidest erinevate suuruste saamiseks
            $this->myImage = $this->resize_image($this->myTempImage, $imageWidth, $imageHeight, round($imageWidth/$sizeRatio), round($imageHeight/$sizeRatio));	
		}
		private function resize_image($image, $origW, $origH, $origX, $origY, $w, $h){
			$dst = imagecreatetruecolor($w, $h);
			//Säilitan png jaoks läbipaistvuse
			imagesavealpha($dst, true); //säilitab läbipaistvuseta
			$transColor = imagecolorallocatealpha($dst, 0, 0, 0, 127);
			imageFill($dst, 0, 0, $transColor);
			imagecopyresampled($dst, $image, 0, 0, $origX, $origY, $w, $h, $origW, $origH);
			return $dst;
		}
		
		public function addWatermark($marginHor, $marginVer){
			//Lisame vesimärgi
            $stamp = imagecreatefrompng("../../graphics/hmv_logo.png");
            $stampWidth = imagesx($stamp);
            $stampHeight = imagesy($stamp);
            $stampPosX = imagesx($this->myImage) - $stampWidth - $marginHor;
            $stampPosY = imagesy($this->myImage) - $stampHeight - $marginVer;
            //imagecopy kleebib ühe pildi teise peale
            imagecopy($this->myImage, $stamp, $stampPosX, $stampPosY, 0, 0, $stampWidth, $stampHeight);
		}
		
		public function readExif(){
			//Loen EXIF infot
            @$exif = exif_read_data($this->tempName,"ANY_TAG", 0, true);
            //var_dump($exif);
            if(!empty($exif["DateTimeOriginal"])){
                $this->exifToImage = "Pilt tehti: " .$exif["DateTimeOriginal"];
            } else {
                $this->exifToImage = "Pildistamise aeg ei ole teada";
            }
		}
		
		public function addTextWatermark($text){
			//Teksti värv
            //imagecolorallocate - ilma läbipaistvuseta
            //alpha 0 - 127   
            $textColor = imagecolorallocatealpha($this->myImage, 255, 255, 255, 50);
            imagettftext($this->myImage, 20, 0, 10, 25, $textColor, "style/TIMES (2017).TTF", $text);
		}
		
		public function savePhoto ($directory, $fileName){
			//Salvestame pildifaili
			$target_file = $directory .$fileName;
			if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
                if(imagejpeg($this->myImage, $target_file, 90)){
                    $notice = "true";
                } else {
                    $notice = "false";
                }
            }
            
            if($this->imageFileType == "png"){
                if(imagepng($this->myImage, $target_file, 6)){
                    $notice = "true";
                } else {
                    $notice = "false";
                }
            }
            
            if($this->imageFileType == "gif"){
                if(imagegif($this>myImage, $target_file)){
                    $notice = "true";
                } else {
                    $notice = "false";
                }
            }
			return $notice;
		}
		

		
		public function saveOriginal($directory, $filename){
			//Kui kõik on korras laadi fail üles, teeb koopia tempfaili
			$target_file = $directory .$fileName;
            if(move_uploaded_file($this->tempName, $target_file)){
                $notice = "true";
            } else {
                $notice = "false";
            }
		}
		
		
		public function createThumbnail($directory, $filename, $width, $height){
			$imageWidth = imagesx($this->myTempImage);
			$imageHeight = imagesy($this->myTempImage);
			
			$sizeRatio = 1;
			//arvutan suuruse jagaja ning teen kindlaks, kust tuleb ruudu kujuliseks kärpida
			if($imageWidth > $imageHeight){
				$sizeRatio = $imageWidth / $width;
				$origX = round(($imageWidth - $imageHeight) / 2);
				$origY = 0;
				$cutSize = $imageHeight;
			} else {
				$sizeRatio = $imageHeight / $height;
				$origX = 0;
				$origY = round(($imageHeight - $imageWidth) / 2);
				$cutSize = $imageWidth;
			}
			
			//pilt, soovitud lõigatav laius, lõigatav kõrgus, lõike x, lõike y, uus laius, uus kõrgus
			$myThumbnail = $this->resize_image($this->myTempImage, $cutSize, $cutSize, $origX, $origY, $width, $height);
			$target_file = $directory .$filename;
			//Thumbnail on igal juhul jpg
			if(imagejpeg($myThumbnail, $target_file, 90)){
				$notice = "Pisipildi salvestamine õnnestus! ";
			} else {
				$notice = "Pisipildi salvestamine ebaõnnestus! ";
			}
		}
		
		public function clearImages(){
			imagedestroy($this->myTempImage);
			imagedestroy($this->myImage);
		}
	}//class'i lõpp

?>