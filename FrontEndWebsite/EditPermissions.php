<?php
if(!empty($_COOKIE["BedAndCountySessionToken"]) or empty($_GET['UserID'])){
	//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();

	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		$UserResults = $statement->fetchAll();
		$UserID = $UserResults[0][0];
		$UserName = $UserResults[0][1];
		$Email = $UserResults[0][2];
		$FirstName = $UserResults[0][3];
		$SecondName = $UserResults[0][4];
		$DateOfBirth = $UserResults[0][5];
		$Password = $UserResults[0][5];

		//Verifying Permission is valid.
		$TokenQuery = "SELECT PermissionName FROM UserSessions 
		INNER JOIN UserAccounts ON UserSessions.UserID = UserAccounts.UserID
		INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
		INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
		INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
		INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
		WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";

		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		$AccountEditing = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "CourseMapView"){
				$AccountEditing = true;
			}
		}
		$FocusUserQuery = "SELECT PermissionName FROM UserAccounts
		INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
		INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
		INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
		INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
		WHERE UserAccounts.UserID = '" . $_GET['UserID'] . "';";

		$PermUserQuery = $PDO -> prepare($FocusUserQuery);
		$PermUserQuery -> execute();
		$PermUserResult= $PermUserQuery -> fetchAll();
		echo $PermUserResult;
		$ValidDelete = true;
		foreach($PermUserQuery as $UserPerms){
			foreach($UserPerms as $Item){
				if($Item == "Admin"){
					$ValidDelete = false;
				}
			}
		}
		if($ValidDelete){
			$DeleteUserStatement = "DELETE FROM UserAccounts WHERE UserID = " . $_GET['UserID'] . ";";
			$DeleteUserQuery = $PDO -> prepare($DeleteUserStatement);
			$DeleteUserQuery -> execute();
		}
		
		header("Location: AdminConsole.php");
	}else{
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
	}
}else{
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
}

if(!$AccountEditing){
	header("Location: Index.php");
	die();
}
?>