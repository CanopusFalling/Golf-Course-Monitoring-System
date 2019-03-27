<?php
if(!empty($_COOKIE["BedAndCountySessionToken"]) or empty($_GET['UserID'])){
	//New database connection
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	//Get the user ID attached to the session token.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	//Get all the results.
	$SessionResults = $statement->fetchAll();
	
	//Get all the user details concerining that User ID.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		//If the cookie is valid then return all the results and then split into individual variables.
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
		
		//Get all the permissions of the user.
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Check that one of the permissions they have is account editing.
		$AccountEditing = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "PermissionAssignment"){
				$AccountEditing = true;
			}
		}
		
		//Get all the details about the user that is bieng edited.
		$UserQuery = $PDO -> prepare("SELECT * FROM UserAccounts WHERE UserID = " . $_GET['UserID']);
		$UserQuery -> execute();
		$Users = $UserQuery->fetchAll();
		$FocusUserID = $Users[0][0];
		$FocusUserName = $Users[0][1];
		$FocusEmail = $Users[0][2];
		$FocusFirstName = $Users[0][3];
		$FocusSecondName = $Users[0][4];
		$FocusDateOfBirth = $Users[0][5];
		$FocusPassword = $Users[0][5];
		
		//Checks that the user is able to edit accouts.
		if($AccountEditing){
			
			//Checks if the posts for the form are set.
			if(isset($_POST['Admin']) and isset($_POST['CourseMonitor']) and isset($_POST['VerifiedUser'])){
				//Clears the permissions of that user before they can be replaced.
				$PermClearQuery = "DELETE FROM PermissionGroupAllocation WHERE UserID = " . $FocusUserID . ";";
				//Makes an array of all of the insert that will need to be made to allocate the user permissions.
				$Inserts = array(
					0 => "(" . $FocusUserID . ", 1)",
					1 => "(" . $FocusUserID . ", 2)",
					2 => "(" . $FocusUserID . ", 3)"
				);
				//Creates an array of placeholders representing each permission as a 0 or 1.
				$PermAddList = array(
					0 => 0,
					1 => 0,
					2 => 0
				);
				
				//Cheks the permissions and alters them accordingly.
				if($_POST["Admin"]){
					$PermAddList[0] = 1;
				}
				if($_POST["CourseMonitor"]){
					$PermAddList[1] = 1;
				}
				if($_POST["VerifiedUser"]){
					$PermAddList[2] = 1;
				}
				
				//Compiles the start of the query to add all of the permissions back.
				$PermAddQuery = "INSERT INTO PermissionGroupAllocation (UserID, PermissionGroupID)";
				$ItemCount = 0;
				
				//Adds the correct entry for each permission that the user has.
				for($i = 0; $i <= 2; $i++){
					if($PermAddList[$i] == 1){
						if($ItemCount == 0){
							//If this is the first permission to be added then add the values section of the command.
							$PermAddQuery = $PermAddQuery . " VALUES " . $Inserts[$i];
						}else{
							//If this is not the first permission to be added then add a comma after the previous add.
							$PermAddQuery = $PermAddQuery . ", " . $Inserts[$i];
						}
						//keep count of the items appended.
						$ItemCount = $ItemCount + 1;
					}
					if($i == 2){
						//Adds a semicolon onto the end of the string for the final item.
						$PermAddQuery = $PermAddQuery . ";";
					}
				}
				
				//Runs all of the SQL queries.
				$PermClearQueryPreped = $PDO -> prepare($PermClearQuery);
				$PermClearQueryPreped -> execute();
				//Checks if items have been added.
				$AddItems = false;
				foreach($PermAddList as $Perm){
					if($Perm == 1){
						$AddItems = true;
					}
				}
				
				//If the items are bieng added then run the query.
				if($AddItems){
					$PermAddQueryPreped = $PDO -> prepare($PermAddQuery);
					$PermAddQueryPreped -> execute();
				}
			}
			
			//Checks the permissions of the user that is being viewed.
			$PermUserQuery = "SELECT PermissionGroupName FROM UserAccounts
			INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
			INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
			INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
			INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
			WHERE UserAccounts.UserID = '" . $_GET['UserID'] . "';";
			
			//Runs the above query and gets all of the user's permissions.
			$FocusUserQuery = $PDO -> prepare($PermUserQuery);
			$FocusUserQuery -> execute();
			$PermUserResult= $FocusUserQuery -> fetchAll();
			
			//Creates an array to represenr all of the permissions
			$PermList = array(
				0 => 0,
				1 => 0,
				2 => 0
			);
			//Set all the perms to 0 by default.
			$Permlist[0] = 0;
			$Permlist[1] = 0;
			$Permlist[2] = 0;
			
			//Checks all of the perms and changes the values accordingly.
			foreach($PermUserResult as $User){
				foreach($User as $Perm){
					if($Perm == "Admin"){
						$Permlist[0] = true;
					}
					if($Perm == "CourseMonitor"){
						$Permlist[1] = true;
					}
					if($Perm == "VerifiedUser"){
						$Permlist[2] = true;
					}
				}
			}
		}else{
			//If the user doesn't have account edditing then logs them out of the system and redirects them to the home.
			setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
			header("Location: Index.php");
			//Stops the code from progrssing further
			die();
		}
	}else{
		//If the cookie is invalid then clear it then redirect them to the home page.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Stops the code from progrssing further
		die();
	}
}else{
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
	//Stops the code from progrssing further
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External code links.-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Frames for animation of background slideshow.-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation is all run from here-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class='Block' onclick="window.location.href = 'AdminConsole.php'">Admin Console</li>
	<li class="TopLogin"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

<!--All of the permission editing form is housed within here.-->
<div class="FullPannelSpacer">
<Form class="DetailsForm" method="post">
<div class="PannelItem">
<!--Insertation of the users name so that the admin can remember who they are editing.-->
Editing Permissions For User: <?php echo $FocusFirstName . " " . $FocusSecondName;?>
</div>

<!--Pannel for all of the checkboxes.-->
<div class="PannelItem">
<!--All of the tickboxes are ticked with the current permission when the form is first loaded up.-->
<label>Admin
	<input type="hidden" name='Admin' value="0">
	<input type="checkbox" name='Admin' <?php if($Permlist[0]){echo "checked='checked'";} ?>>
</label></br>
<label >Course Monitor
	<input type="hidden" name='CourseMonitor' value="0">
	<input type="checkbox" name='CourseMonitor' <?php if($Permlist[1]){echo "checked='checked'";} ?>>
</label></br>
<label>Verified User
	<input type="hidden" name='VerifiedUser' value="0">
	<input type="checkbox" name='VerifiedUser' <?php if($Permlist[2]){echo "checked='checked'";} ?>>
</label></br>
</div>

<!--Submit button for the form.-->
<Button type="Submit" class="ButtonLargeText">Change Permissions</Button>
</div>
</Form>

<!--Course Logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>