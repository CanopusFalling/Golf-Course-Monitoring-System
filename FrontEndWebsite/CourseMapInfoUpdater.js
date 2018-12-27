function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function UpdateMap(){
	var xhttp = new XMLHttpRequest();
	
	var Cookie = getCookie("BedAndCountySessionToken");
	
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("InsertDiv").innerHTML = this.responseText;
		}
	}
	var SitePHP = "CourseMapInfoUpdater.php?Token=" + Cookie;
	xhttp.open("GET", SitePHP, true);
	xhttp.send();
}

window.onload = function(){
	UpdateMap();
	setInterval(UpdateMap, 5000);
}