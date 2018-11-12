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
$time = time();
$time = $time - 2000;
$date = date('m-d-Y h:i:s', $time);
$Query = "SELECT * FROM GPSData WHERE DateTimeStamp >= '" . $date . "';";



$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$Output = $PDO->execute($Query);
$Data = $Output->fetchaAll();

foreach($Data as $Row){
	echo $Row['0'] . $Row['1'] . $Row['2'] . $Row['3'] ."\n";
}
?>
<Head>
<link rel="stylesheet" href="Styles.css">
<Style>
<?php
$Count = 0;

foreach($Output as $Row){
	echo $Row['0'] . $Row['1'] . $Row['2'] . $Row['3'] ."\n";
}

foreach($Output as $Row){
	echo ".Point-Overlay" . $Count . "{\n	position: absolute;\n	top: " . (($Row['Longitude'] - $TenPXMarkLong)/($HunderedPXMarkLong-$TenPXMarkLong))*100 . "px;\n	left: " . (($Row['Latitude'] - $TenPXMarkLat)/($HunderedPXMarkLat-$TenPXMarkLat))*100 . "px;";
	$Count = $Count + 1;
}


?>
.Point-Overlay{
	position: absolute;
	top: 100px;
	left: 100px;
}
</Style>
</Head>

<!--<div class="Course-Image"><img src="ImageGallery/BMSMap.png" alt="Course Map"></div>-->
<!--<div class="Course-Image"><img src="ImageGallery/CourseMap.png" alt="Course Map" width="1300px" height="800px"></div>-->

<body>
<?php
foreach($Output as $Row){
	echo $Row['0'] . $Row['1'] . $Row['2'] . $Row['3'] ."\n";
}
?>
<div class="Point-Overlay"><img src="ImageGallery/Point.png" alt="Course Map"></div>
</body>


