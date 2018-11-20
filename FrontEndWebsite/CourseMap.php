<?php

//Location Cordinates

//BMS Testing (Comment Out During Actual Use)
$TenPXMarkLong = 52.151330;
$TenPXMarkLat = -0.485627;
$HunderedPXMarkLong = 52.150642;
$HunderedPXMarkLat = -0.484531;

//Bedford And County Location
//To be done

//Database Querying
//Query Generation
$time = time();
$timeMin = $time - 100;
$date = date('m-d-Y H:i:s', $timeMin);
$Query = "SELECT * FROM GPSData WHERE DateTimeStamp >= '" . $date . "';";

//Database connection and execution
//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
$statement = $PDO->prepare($Query);
$statement->execute();
$results = $statement->fetchAll();



?>
<Head>
<link rel="stylesheet" href="Styles.css">
<Script rel="CourseMapLocationUpdater.js">
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

var points =[{
id: "1234",
x: 200,
y: 300
},
{
id: "2345",
x: 300,
y: 200
}
]

window.onload = function(){
	setInterval(UpdateMap, 5000);
}
</Script>
<style>
.Point-Overlay{
	display: circle;
	position: absolute;
	width: 5px;
	height: 5px;
}
</style>

<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="TopBlock" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="Login Block" href="Login.php">Login</li>
	<li class="Login Block" href="SignIn.php">Sign Up</li>
</Nav>


</Style>
</Head>

<div class="Course-Image"><img src="ImageGallery/BMSMap.png" alt="Course Map" (width="1300px" height="800px")></div>
<!--<div class="Course-Image"><img src="ImageGallery/CourseMap.png" alt="Course Map" width="1300px" height="800px"></div>-->

<div id="InsertDiv"></div>

</body>


