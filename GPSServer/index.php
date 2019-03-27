<?php
//Echos all of the sent data.
echo $_GET["ID"] . " : " . $_GET["Lat"] . " : " . $_GET["Long"];

//Gets the curent data into an SQL friendly format.
$date = date('m-d-Y H:i:s', time());
$Command = "INSERT INTO GPSData (PhoneID, DateTimeStamp, Longitude, Latitude) VALUES (" . $_GET["ID"] . ", '" . $date . "', " . $_GET["Lat"] . ", " . $_GET["Long"] . ");";

//Echos the command used in SQL.
echo "\n" . $Command;

//Connects to the database and executes the command.
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
$PDO->query($Command);

?>
