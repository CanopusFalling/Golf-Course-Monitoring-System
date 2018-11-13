<?php
//Refresh code
$page = $_SERVER['PHP_SELF'];
$sec = "5";
header("Refresh: $sec; url=$page");

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
$time = $time - 1000;
$date = date('m-d-Y H:i:s', $time);
$Query = "SELECT * FROM GPSData WHERE DateTimeStamp >= '" . $date . "';";

//Database connection and execution
$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$statement = $PDO->prepare($Query);
$statement->execute();
$results = $statement->fetchAll();

//Testing data
//print_r($results);
//foreach($results as $row)
//{
//	print_r($row);
//}

?>
<Head>
<link rel="stylesheet" href="Styles.css">
<Style>
<?php
$Count = 0;

foreach($results as $Row){
	$TopPX = intval((($Row['Longitude'] - $TenPXMarkLong)/($HunderedPXMarkLong-$TenPXMarkLong))*90);
	$LeftPX = intval((($Row['Latitude'] - $TenPXMarkLat)/($HunderedPXMarkLat-$TenPXMarkLat))*90);
	echo ".Point-Overlay" . $Count . "{\n	position: absolute;\n	top: " . $TopPX . "px;\n	left: " . $LeftPX . "px;\n}";
	$Count = $Count + 1;
}


?>

/*.Point-Overlay{
	position: absolute;
	top: 100px;
	left: 100px;
}*/
</Style>
</Head>

<div class="Course-Image"><img src="ImageGallery/BMSMap.png" alt="Course Map" (width="1300px" height="800px")></div>
<!--<div class="Course-Image"><img src="ImageGallery/CourseMap.png" alt="Course Map" width="1300px" height="800px"></div>-->

<body>
<?php
$Count = 0;
foreach($results as $Row){
	echo "<div class='Point-Overlay" . $Count . "'><img src='ImageGallery/Point.png' alt='Player'></div>";
	$Count = $Count + 1;
}

//Testing data
echo "<PRE>";

print_r($results);

?>
<!--<div class="Point-Overlay"><img src="ImageGallery/Point.png" alt="Player"></div>-->
</body>


