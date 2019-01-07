<?php
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

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

if($AllowedToViewDetailed){
	
	echo "
	<div class='FullPannelSpacer'>
	<div class='FullPannel'>
	Players On Course</br></br>
	<table id='Accounts'>
	<tr>
		<th>UserName</th>
		<th>Time Out</th>
		<th>Position</th>
	</tr>
	";
	
	$PhoneBookingsQuery = "SELECT UserAccounts.UserID, UserAccounts.UserName, PhoneBookings.DateTimeOut, GPSData.Longitude, GPSData.Latitude FROM PhoneBookings
	INNER JOIN UserAccounts ON PhoneBookings.UserID = UserAccounts.UserID INNER JOIN GPSData ON UserAccounts.UserID = GPSData.UserID
	GROUP BY UserAccounts.UserID
	ORDER BY UserAccounts.UserID, GPSData.DateTimeStamp DESC;";
	$UserQuery = $PDO -> prepare($PhoneBookingsQuery);
	$UserQuery -> execute();
	$Users = $UserQuery->fetchAll();
	
	$UserCount = NULL;
	foreach($Users as $User){
		if(!($UserCount == $User['UserID'])){
			if($User["DateTimeOut"] == null){
				echo "<tr onclick=\"window.location.href = 'CloseSession.php?BookingID=" . $User[0] . "'\">";
			}else{
				echo "<tr>";
			}
			echo "<td>" . $User["UserName"] . "</td>";
			echo "<td>" . $User["DateTimeOut"] . "</td>";
			echo "<td>" . $User["Longitude"] . ", " . $User["Latitude"] . "</td>";
			echo "</tr>";
			$UserCount = $User['UserID'];
		}
	}
	
	echo "
	
	</div>
	</div>
	";
}else{
	echo "
	<div class='PannelSpacer'>
	<div class='Pannel'>
	You are not permitted to view the details of the players on the course.
	</div>
	</div>
	";
}
?>