function UpdateMap(){
	var xhttp = new XMLHttpRequest();
	
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("InsertDiv").innerHTML = this.responseText;
		}
	}
	xhttp.open("GET", "CourseMapUpdater.php", true);
	xhttp.send();
}

window.onload = function(){
	setInterval(UpdateMap, 5000);
}