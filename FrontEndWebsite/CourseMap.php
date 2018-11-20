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
<Script src="CourseMapLocationUpdater.js"></Script>

<div class="SlideshowFrame"></div>

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


