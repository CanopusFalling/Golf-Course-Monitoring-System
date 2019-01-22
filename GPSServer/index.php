<?php
echo $_GET["ID"] . " : " . $_GET["Lat"] . " : " . $_GET["Long"];

$date = date('m-d-Y H:i:s', time());
$Command = "INSERT INTO GPSData (PhoneID, DateTimeStamp, Longitude, Latitude) VALUES (" . $_GET["ID"] . ", '" . $date . "', " . $_GET["Lat"] . ", " . $_GET["Long"] . ");";

echo "\n" . $Command;

//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
$PDO->query($Command);

?>
