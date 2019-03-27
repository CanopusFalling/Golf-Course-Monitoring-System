function getCookie(cname) {
	//Gets the cookie name that is passed into the function and appends an eaquels to it.
    var name = cname + "=";
	//Decodes the cookie into the variable.
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
	//Opens a new httpx stream
	var xhttp = new XMLHttpRequest();
	
	//Gets the cookie from the website.
	var Cookie = getCookie("BedAndCountySessionToken");
	
	//Ensures that the html is pulled down only when the state is correct and the page has loaded.
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//Places all of the html into the insert div
			document.getElementById("InsertDiv").innerHTML = this.responseText;
		}
	}
	//Creates the GET protocol for sending data in the url of the site.
	var SitePHP = "CourseMapUpdater.php?Token=" + Cookie;
	xhttp.open("GET", SitePHP, true);
	//Sends the request.
	xhttp.send();
}

window.onload = function(){
	//Runs the Update map function.
	UpdateMap();
	//Runs the update map function every 10000 milliseconds or 10 seconds.
	setInterval(UpdateMap, 10000);
}