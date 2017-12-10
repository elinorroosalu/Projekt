var modal;
var modalImg;
var captionText;
var span;
var photoDir = "../photos/";

window.onload = function(){
	modal = document.getElementById('myModal');
	modalImg = document.getElementById("modalImage");
	captionText = document.getElementById("caption");
	span = document.getElementsByClassName("close")[0];
	var allThumbs = document.getElementById("allThumbnails").getElementsByTagName("img");
	var thumbCount = allThumbs.length;
	for (var i = 0; i < thumbCount; i ++){
		allThumbs[i].addEventListener("click",openModal);
	}
	span.addEventListener("click", closeModal);
	modalImg.addEventListener("click", closeModal);
}

function openModal(e){
	modal.style.display = "block";
    modalImg.src = photoDir + e.target.id;
	if(e.target.title.length > 0){
		captionText.innerHTML = e.target.title + ": " + e.target.Heading + "-" +e.target.Descript;
	} else {
		captionText.innerHTML = e.target.alt;
	}
}

function closeModal(){ 
  modal.style.display = "none";
}