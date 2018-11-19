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
$timeMin = $time - 100;
$date = date('m-d-Y H:i:s', $timeMin);
$Query = "SELECT * FROM GPSData WHERE DateTimeStamp >= '" . $date . "';";

//Database connection and execution
//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
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

	$dtime = DateTime::createFromFormat("m-d-Y H:i:s", $Row[1]);
	$TimeMade = $dtime->getTimestamp();

<<<<<<< HEAD
	$HexAppend = dechex(intval(intval($TimeMade-$timeMin)));
=======
	$HexAppend = dechex(256-(intval(intval($TimeMade-$timeMin))*(256/100)));
>>>>>>> c2a95a99b265263517cd08ea786c9f58a5b9d093
	
	if(strlen($HexAppend) == 1){
		$HexAppend = "0" . $HexAppend;
	}

	$HexCode = "#" . $HexAppend . $HexAppend . "ff";
	echo ".Point-Overlay" . $Count . "{\n	display: circle;\n	position: absolute;\n	width: 5px;\n	height: 5px;\n		background: " . $HexCode . ";\n	top: " . $TopPX . "px;\n	left: " . $LeftPX . "px;\n}";
	$Count = $Count + 1;
}


?>

</Style>
</Head>

<div class="Course-Image"><img src="ImageGallery/BMSMap.png" alt="Course Map" (width="1300px" height="800px")></div>
<!--<div class="Course-Image"><img src="ImageGallery/CourseMap.png" alt="Course Map" width="1300px" height="800px"></div>-->

<body>
<?php
$Count = 0;
foreach($results as $Row){
	echo "<div class='Point-Overlay" . $Count . "'></div>";
	$Count = $Count + 1;
	$dtime = DateTime::createFromFormat("m-d-Y H:i:s", $Row[1]);
	$TimeMade = $dtime->getTimestamp();
}

//Testing data
echo "<PRE>";

print_r($results);

?>
<div class="Point-Overlay"></div>
</body>


