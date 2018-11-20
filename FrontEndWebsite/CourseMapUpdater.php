<?php
//Location Cordinates

//BMS Testing (Comment Out During Actual Use)
$TenPXMarkLong = 52.151330;
$TenPXMarkLat = -0.485627;
$HunderedPXMarkLong = 52.150642;
$HunderedPXMarkLat = -0.484531;

//Bedford And County Location
//To be done

//Getting Data
//$StartTime = $_REQUEST["StartTime"];
//$EndTime = $_REQUEST["EndTime"];
//$PhoneID = $_REQUEST["ID"];

//Database Querying
//Query Generation
//$PhoneIDCondition = " AND PhoneID = " . PhoneID;
//if(PhoneID == ""){
//	$PhoneIDCondition = "";
//}
//$Query = "SELECT * FROM GPSData WHERE DateTimeStamp >= '" . $StartTime . "' AND WHERE DateTimeStamp <= '" . $EndTime . "'" . PhoneIDCondition . ";" ;

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

$Count = 0;
foreach($results as $Row){
	$TopPX = intval((($Row['Longitude'] - $TenPXMarkLong)/($HunderedPXMarkLong-$TenPXMarkLong))*90);
	$LeftPX = intval((($Row['Latitude'] - $TenPXMarkLat)/($HunderedPXMarkLat-$TenPXMarkLat))*90);

	$dtime = DateTime::createFromFormat("m-d-Y H:i:s", $Row[1]);
	$TimeMade = $dtime->getTimestamp();

	$HexAppend = dechex(256-(intval(intval($TimeMade-$timeMin))*(256/100)));
	
	if(strlen($HexAppend) == 1){
		$HexAppend = "0" . $HexAppend;
	}
	
	$HexCode = "#" . $HexAppend . $HexAppend . "ff";
	
	echo "<div class='Point-Overlay' style='background: " . $HexCode . ";top: " . $TopPX . "px;left: " . $LeftPX . "px;'></div>";
	$Count = $Count + 1;
	$dtime = DateTime::createFromFormat("m-d-Y H:i:s", $Row[1]);
	$TimeMade = $dtime->getTimestamp();
}

//Testing data
//echo "<PRE>";

//print_r($results);

?>

