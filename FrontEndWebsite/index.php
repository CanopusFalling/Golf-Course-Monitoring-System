<?php

//Location Cordinates

//BMS Testing (Comment Out During Actual Use)
$TenPXMarkLong = 52.151415;
$TenPXMarkLat = -0.485627;
$HunderedPXMarkLong = 52.150668;
$HunderedPXMarkLat = -0.484494;

//Bedford And County Location
//To be done

//Database Querying
$Date = date('m-d-Y h:i:s', strtotime('-1 hour'));
$Query = "SELECT * FROM GPSData WHERE DateTimeStamp >= " . $MinDate . ";";



$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$Output = $PDO->Query($Command);
?>
<Head>
<link rel="stylesheet" href="Styles.css">
<Style>
<?php
foreach($Output as $Row){
	echo ".Point-Overlay{\n	position: absolute;\n	top: " . (($Row['Longitude'] - $TenPXMarkLong)/($HunderedPXMarkLong-$HunderedPXMarkLong))*100 . "px;\n	left: " . (($Row['Latitude'] - $TenPXMarkLat)/($HunderedPXMarkLat-$HunderedPXMarkLat))*100 . "px;";
}


?>
.Point-Overlay{
	position: absolute;
	top: 100px;
	left: 100px;
}
</Style>
</Head>

<div class="Course-Image"><img src="ImageGallery/BMSMap.png" alt="Course Map" (!--width="1300px" height="800px"--)></div>
<?php
foreach($Output as $Row){
	echo ""$Row["UserID"] . "\n";
}
?>
<div class="Point-Overlay"><img src="ImageGallery/Point.png" alt="Course Map"></div>


