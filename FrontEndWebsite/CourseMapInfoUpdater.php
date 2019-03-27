<?php
//Opend the database connection.
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

//Verifying Permission is valid.
$TokenQuery = "SELECT PermissionName FROM UserSessions 
INNER JOIN UserAccounts ON UserSessions.UserID = UserAccounts.UserID
INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
WHERE SessionToken = '" . $_GET['Token'] . "';";

//Prepares the statement, this is very important here as sql injections are particualarly easy at this point.
$TokenStatement = $PDO->prepare($TokenQuery);
//Executes the command.
$TokenStatement->execute();
//Gets all of the user's permissions into $TokenQueryResults
$TokenQueryResults = $TokenStatement->fetchAll();

//Checks if the permissions list allowes the user to view and if they are allowed to view detailed breakdowns.
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

//If the user is allowed to view then this us run.
if($AllowedToViewDetailed){
	//this shows up if the user's permission is invalid.
	echo "
	<div class='PannelSpacer'>
	<div class='Pannel'>
	Players On Course</br></br>
	<table id='Accounts'>
	<tr>
		<th>UserName</th>
		<th>Time Out</th>
		<th>Position</th>
	</tr>
	";
	
	//The query to find all of the players on the course at the moment.
	$PhoneBookingsQuery = "SELECT UserAccounts.UserID, UserAccounts.UserName, PhoneBookings.DateTimeOut, GPSData.Longitude, GPSData.Latitude, PhoneBookings.BookingID FROM PhoneBookings
	INNER JOIN UserAccounts ON PhoneBookings.UserID = UserAccounts.UserID INNER JOIN GPSData ON PhoneBookings.PhoneID = GPSData.PhoneID
	WHERE PhoneBookings.DateTimeIn IS NULL
	GROUP BY UserAccounts.UserID
	ORDER BY GPSData.DateTimeStamp ASC;";
	
	//Executes the query and returns all of the users int $Users
	$UserQuery = $PDO -> prepare($PhoneBookingsQuery);
	$UserQuery -> execute();
	$Users = $UserQuery->fetchAll();
	
	//Itterates through all of the users and then creates a table of all of them with the correct headders that will sit to the right of the course map.
	$UserCount = NULL;
	foreach($Users as $User){
		if(!($UserCount == $User['UserID'])){
			echo "<tr onclick=\"window.location.href = 'UserView.php?BookingID=" . $User['BookingID'] . "'\">";
			echo "<td>" . $User["UserName"] . "</td>";
			echo "<td>" . $User["DateTimeOut"] . "</td>";
			echo "<td>" . $User["Longitude"] . ", " . $User["Latitude"] . "</td>";
			echo "</tr>";
			$UserCount = $User['UserID'];
		}
	}
	//Closes all the table divs
	echo "
	
	</div>
	</div>
	";
}else{
	//If the user shouldn't be allowed then this is the message that pops up.
	echo "
	<div class='PannelSpacer'>
	<div class='Pannel'>
	You are not permitted to view the details of the players on the course.
	</div>
	</div>
	";
}
?>