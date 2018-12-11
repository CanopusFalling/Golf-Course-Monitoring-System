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
			if($Row[0] == "PermissionAssignment"){
				$AccountEditing = true;
			}
		}
		
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
		
		if($AccountEditing){
			
			if(isset($_POST['Admin']) and isset($_POST['CourseMonitor']) and isset($_POST['VerifiedUser'])){
				$PermClearQuery = "DELETE FROM PermissionGroupAllocation WHERE UserID = " . $FocusUserID . ";";
				$Inserts = array(
					0 => "(" . $FocusUserID . ", 1)",
					1 => "(" . $FocusUserID . ", 2)",
					2 => "(" . $FocusUserID . ", 3)"
				);
				$PermAddList = array(
					0 => 0,
					1 => 0,
					2 => 0
				);
				if($_POST["Admin"]){
					$PermAddList[0] = 1;
				}
				if($_POST["CourseMonitor"]){
					$PermAddList[1] = 1;
				}
				if($_POST["VerifiedUser"]){
					$PermAddList[2] = 1;
				}
				
				
				$PermAddQuery = "INSERT INTO PermissionGroupAllocation (UserID, PermissionGroupID)";
				$ItemCount = 0;
				
				for($i = 0; $i <= 2; $i++){
					if($PermAddList[$i] == 1){
						if($ItemCount == 0){
							$PermAddQuery = $PermAddQuery . " VALUES " . $Inserts[$i];
						}else{
							$PermAddQuery = $PermAddQuery . ", " . $Inserts[$i];
						}
						$ItemCount = $ItemCount + 1;
					}
					if($i == 2){
						$PermAddQuery = $PermAddQuery . ";";
					}
				}
				
				$PermClearQueryPreped = $PDO -> prepare($PermClearQuery);
				$PermClearQueryPreped -> execute();
				$AddItems = false;
				foreach($PermAddList as $Perm){
					if($Perm == 1){
						$AddItems = true;
					}
				}
				
				if($AddItems){
					$PermAddQueryPreped = $PDO -> prepare($PermAddQuery);
					$PermAddQueryPreped -> execute();
				}
			}
			
			$PermUserQuery = "SELECT PermissionGroupName FROM UserAccounts
			INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
			INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
			INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
			INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
			WHERE UserAccounts.UserID = '" . $_GET['UserID'] . "';";

			$FocusUserQuery = $PDO -> prepare($PermUserQuery);
			$FocusUserQuery -> execute();
			$PermUserResult= $FocusUserQuery -> fetchAll();
			
			$PermList = array(
				0 => 0,
				1 => 0,
				2 => 0
			);
			$Permlist[0] = 0;
			$Permlist[1] = 0;
			$Permlist[2] = 0;
			
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
			
		}
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

<html>
<head>
<title>Bedford And County Golf Course</title>
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class='Block' onclick="window.location.href = 'AdminConsole.php'">Admin Console</li>
	<li class="TopLogin"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

<div class="FullPannelSpacer">
<Form class="DetailsForm" method="post">
<div class="PannelItem">
Editing Permissions For User: <?php echo $FocusFirstName . " " . $FocusSecondName;?>
</div>

<div class="PannelItem">
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
<Button type="Submit" class="ButtonLargeText">Change Permissions</Button>
</div>
</Form>
</body>
</html>