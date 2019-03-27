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
	//Makes a new xhttp request.
	var xhttp = new XMLHttpRequest();
	//Gets the cookie value
	var Cookie = getCookie("BedAndCountySessionToken");
	//Makes sure that the data is retrived after the page loads.
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//Moves all of the HTML from that site into the site.
			document.getElementById("InsertDiv").innerHTML = this.responseText;
		}
	}
	//Prepares the URL to be used and the GET data with the user ID.
	var SitePHP = "UserViewCourseMapUpdater.php?Token=" + Cookie + "&BookingID=" + document.getElementById('BookingID').innerHTML;
	xhttp.open("GET", SitePHP, true);
	//Sends the request.
	xhttp.send();
}

window.onload = function(){
	//Updates the map
	UpdateMap();
	//Runs the Update map every 10000 miliseconds.
	setInterval(UpdateMap, 10000);
}