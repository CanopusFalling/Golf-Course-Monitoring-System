<?php
if(!empty($_COOKIE["BedAndCountySessionToken"]) or empty($_GET['UserID'])){
	//New database connection
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

	//This finds the user ID affiliated with the session token.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();
	
	//SQL to find all of the user details tied to the user Id from before.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		//Gets all the user details and then seperates it out.
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
		
		//Runs the verification of the user permissions query.
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Checks that the user has permission to be here.
		$AccountEditing = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "CourseMapView"){
				$AccountEditing = true;
			}
		}
		
		//Finds all of the details about the user that is bieng deleted.
		$FocusUserQuery = "SELECT PermissionName FROM UserAccounts
		INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
		INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
		INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
		INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
		WHERE UserAccounts.UserID = '" . $_GET['UserID'] . "';";
		
		//Queries the database for all of the details of the focus user.
		$PermUserQuery = $PDO -> prepare($FocusUserQuery);
		$PermUserQuery -> execute();
		$PermUserResult= $PermUserQuery -> fetchAll();
		echo $PermUserResult;
		$ValidDelete = true;
		//Checks that the user isn't an admin who shouldn't be deleted.
		foreach($PermUserQuery as $UserPerms){
			foreach($UserPerms as $Item){
				if($Item == "Admin"){
					$ValidDelete = false;
				}
			}
		}
		
		//Runs if the delete is allowed by the site.
		if($ValidDelete){
			//Query to delete the user.
			$DeleteUserStatement = "DELETE FROM UserAccounts WHERE UserID = " . $_GET['UserID'] . ";";
			$DeleteUserQuery = $PDO -> prepare($DeleteUserStatement);
			//deletes the user.
			$DeleteUserQuery -> execute();
		}
		//Redirects the user back to the admin console once the delete is done.
		header("Location: AdminConsole.php");
	}else{
		//If an invalid cookie then the user is logged out and sent to the homepage.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
	}
}else{
	//if the user doesn't have acookie then the user is just logged out
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
}

//redirects to the home if the user doesn't have account editing.
if(!$AccountEditing){
	header("Location: Index.php");
}
?>