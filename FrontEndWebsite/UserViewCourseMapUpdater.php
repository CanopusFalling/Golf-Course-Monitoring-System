<?php
//Location Cordinates
//Coordinate mapings
$TenPXMarkLong = 52.151330;
$TenPXMarkLat = -0.485627;
$HunderedPXMarkLong = 52.150642;
$HunderedPXMarkLat = -0.484531;

//For debuging use.
//Getting Data
//$StartTime = $_REQUEST["StartTime"];
//$EndTime = $_REQUEST["EndTime"];
//$PhoneID = $_REQUEST["ID"];

//Database Querying - legacy
//Query Generation
//$PhoneIDCondition = " AND PhoneID = " . PhoneID;
//if(PhoneID == ""){
//	$PhoneIDCondition = "";
//}
//$Query = "SELECT * FROM GPSData WHERE DateTimeStamp >= '" . $StartTime . "' AND WHERE DateTimeStamp <= '" . $EndTime . "'" . PhoneIDCondition . ";" ;

//Gets the time and take away 200 seconds.
$time = time();
$timeMin = $time - 200;
$date = date('m-d-Y H:i:s', $timeMin);
//Querys the database to get all of the GPS recording for the last 200 seconds.
$Query = "SELECT * FROM GPSData
INNER JOIN Phone ON GPSData.PhoneID = Phone.PhoneID 
INNER JOIN PhoneBookings ON Phone.PhoneID = PhoneBookings.PhoneID 
INNER JOIN UserAccounts on PhoneBookings.UserID = UserAccounts.UserID 
WHERE PhoneBookings.BookingID = " . $_GET['BookingID'] . ";";

//Database connection and execution of the query.
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
$statement = $PDO->prepare($Query);
$statement->execute();
$results = $statement->fetchAll();

//Verifying Permission is valid.
//Query generation
$TokenQuery = "SELECT PermissionName FROM UserSessions 
INNER JOIN UserAccounts ON UserSessions.UserID = UserAccounts.UserID
INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
WHERE SessionToken = '" . $_GET['Token'] . "';";
//Execution of the command and return of data.
$TokenStatement = $PDO->prepare($TokenQuery);
$TokenStatement->execute();
$TokenQueryResults = $TokenStatement->fetchAll();

//Checks the permssions that the uer has from the database query.
$AllowedToView = false;
$AllowedToViewDetailed = false;
foreach($TokenQueryResults as $Row){
	if($Row[0] == "CourseMapView"){
		$AllowedToView = true;
	}
	if($Row[0] == "DetailedMapView"){
		$AllowedToViewDetailed = true;
	}
}

//Runs only if the user has a token in the GET data.
if(!empty($_GET['Token'])){
	//Checks the user's permissions.
	if($AllowedToViewDetailed){
		$Count = 0;
		foreach($results as $Row){
			//Gets all the points and the pixel measurements into the correct places.
			$TopPX = intval((($Row['Longitude'] - $TenPXMarkLong)/($HunderedPXMarkLong-$TenPXMarkLong))*90);
			$LeftPX = intval((($Row['Latitude'] - $TenPXMarkLat)/($HunderedPXMarkLat-$TenPXMarkLat))*90);
			
			//Gets the datetime from the SQL format as well as the current time.
			$dtime = DateTime::createFromFormat("m-d-Y H:i:s", $Row[1]);
			$TimeMade = $dtime->getTimestamp();
			
			//Generates a number based on how old the data is.
			$HexAppend = dechex(256-(intval(intval($TimeMade-$timeMin))*(256/200)));
			
			if(strlen($HexAppend) == 1){
				$HexAppend = "0" . $HexAppend;
			}
			
			//Generates a hex code that is blus if the data is new and is white if it's old.
			$HexCode = "#" . $HexAppend . $HexAppend . "ff";
			
			//Checks that the user is allowed to view detaild course info.
			if($AllowedToViewDetailed){
				//Shows the username when hovered over.
				$Title = $Row['UserName'];
			}else{
				//Shows the logitude and lattitude when hovered over.
				$Title = string($Row['Longitude'] . ", " . $Row['Latitude']);
			}
			//Compiles all of the data into one point.
			echo "<div class='Point-Overlay' title='" . $Title . "' style='background: " . $HexCode . ";top: " . $TopPX . "px;left: " . $LeftPX . "px;'></div>";
			$Count = $Count + 1;
			$dtime = DateTime::createFromFormat("m-d-Y H:i:s", $Row[1]);
			$TimeMade = $dtime->getTimestamp();
		}
	}
}




?>