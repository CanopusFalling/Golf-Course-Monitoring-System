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
	readfile("Pathheresoon");
}else{
	echo "
	<div class='Pannel Spacer'>
	<div class='Course-Image'>
	You are not permitted to view the details of the players on the course.
	</div>
	</div>
	";
}
?>