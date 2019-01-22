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
$timeMin = $time - 200;
$date = date('m-d-Y H:i:s', $timeMin);
$Query = "SELECT * FROM GPSData
INNER JOIN Phone ON GPSData.PhoneID = Phone.PhoneID INNER JOIN Bookings On Phone.PhoneID = Bookings.PhoneID WHERE DateTimeStamp >= '" . $date . "';";

//Database connection and execution
//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
$statement = $PDO->prepare($Query);
$statement->execute();
$results = $statement->fetchAll();

//Verifying Permission is valid.
$TokenQuery = "SELECT PermissionName FROM UserSessions 
INNER JOIN UserAccounts ON UserSessions.UserID = UserAccounts.UserID
INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
WHERE SessionToken = '" . $_GET['Token'] . "';";

$TokenStatement = $PDO->prepare($TokenQuery);
$TokenStatement->execute();
$TokenQueryResults = $TokenStatement->fetchAll();

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


if(!empty($_GET['Token'])){
	if($AllowedToView){
		$Count = 0;
		foreach($results as $Row){
			$TopPX = intval((($Row['Longitude'] - $TenPXMarkLong)/($HunderedPXMarkLong-$TenPXMarkLong))*90);
			$LeftPX = intval((($Row['Latitude'] - $TenPXMarkLat)/($HunderedPXMarkLat-$TenPXMarkLat))*90);
		
			$dtime = DateTime::createFromFormat("m-d-Y H:i:s", $Row[1]);
			$TimeMade = $dtime->getTimestamp();
		
			$HexAppend = dechex(256-(intval(intval($TimeMade-$timeMin))*(256/200)));
			
			if(strlen($HexAppend) == 1){
				$HexAppend = "0" . $HexAppend;
			}
			
			$HexCode = "#" . $HexAppend . $HexAppend . "ff";
			
			if($AllowedToViewDetailed){
				$Title = $Row['PhoneID'];
			}else{
				$Title = string($Row['Longitude'] . ", " . $Row['Latitude']);
			}
			echo "<div class='Point-Overlay' title='" . $Title . "' style='background: " . $HexCode . ";top: " . $TopPX . "px;left: " . $LeftPX . "px;'></div>";
			$Count = $Count + 1;
			$dtime = DateTime::createFromFormat("m-d-Y H:i:s", $Row[1]);
			$TimeMade = $dtime->getTimestamp();
		}
		
		if($AllowedToViewDetailed){
			include 'CourseMapInfoUpdater.php';
		}
	}else{
		echo "
		<div class='Pannel Spacer'>
		<div class='Course-Image'>
		Your Account Isn't Permitted to View The Position of Players On The Course.
		</div>
		</div>
		";
	}
}else{
	echo "
	<div class='Pannel Spacer'>
	<div class='Course-Image'>
	Please Log In To View People On The Map.
	</div>
	</div>
	";
}




?>

