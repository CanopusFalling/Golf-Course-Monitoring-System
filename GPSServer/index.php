<?php
echo $_GET["ID"] . " : " . $_GET["Lat"] . " : " . $_GET["Long"];

$date = date('m-d-Y h:i:s', time());
$Command = "INSERT INTO GPSData (UserID, DateTimeStamp, Longitude, Latitude) VALUES (" . $_GET["ID"] . ", '" . $date . "', " . $_GET["Lat"] . ", " . $_GET["Long"] . ");";

echo "\n" . $Command;

$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$PDO->query($Command);

?>
