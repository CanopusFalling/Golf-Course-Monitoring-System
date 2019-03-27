<?php
//Checks if the cookie if the token is empty or not.
if(!empty($_COOKIE["BedAndCountySessionToken"]) or empty($_GET['UserID'])){
	//Database connection.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Select the user ID related to the session token.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();
	
	//Select all the details about the user.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		//Put all the data into different variables approprietley named.
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
		//Runs the permission
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Checks if the user has the permission to edit accounts.
		$AccountEditing = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "PermissionAssignment"){
				$AccountEditing = true;
			}
		}
		
		//Gets all the details about the focus user.
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
		
		
	}else{
		//If the cookie is invalid it is erased and the user is sent back to the homepage.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Ensures no more of the page loads
		die();
	}
}else{
	//If the cookie doesn't exist it is set to null and they are returned to the homepage.
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
	//Ensures no more of the page loads
	die();
}

if(!$AccountEditing){
	//If the access to the page is unautorised the user is turned away from the site.
	header("Location: Index.php");
	//Ensures no more of the page loads
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External scripting references.-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Background frame animation divs.-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation bar is housed here.-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class='Block' onclick="window.location.href = 'AdminConsole.php'">Admin Console</li>
	<li class="TopLogin"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

<!--Main pannel with the user details goes here.-->
<div class="FullPannelSpacer">
<div class="FullPannel">
<div class="PannelItem">
Editing User: <?php echo $FocusFirstName . " " . $FocusSecondName;?>
</div>
</div>
<div class="Pannel">
<div class="PannelItem">
UserName: <?php echo $FocusUserName; ?>
</div>
<div class="PannelItem">
FirstName: <?php echo $FocusFirstName; ?>
</div>
<div class="PannelItem">
LastName: <?php echo $FocusSecondName; ?>
</div>
<div class="PannelItem">
Email: <?php echo $FocusEmail; ?>
</div>
<div class="PannelItem">
Date of Birth: <?php echo $FocusDateOfBirth; ?>
</div>
<!--Button to let the user edit permissions of the user.-->
<Button onclick="window.location.href = 'EditPermissions.php?UserID=<?php echo $_GET['UserID']; ?>'" class="ButtonLargeText">Edit Permissions</Button>
<!--<Button onclick="window.location.href = 'ChangeUserDetails.php'" class="ButtonLargeText">ChangeUserDetails</Button>-->
<!--Later decided that a change user details for the admins wasn't needed.-->
<!--Button to delete the user. Lots of safety measures on this button.-->
<Button onclick="window.location.href = 'DeleteUser.php?UserID=<?php echo $_GET['UserID']; ?>'" class="DeleteButton">DeleteUser</Button>
</div>
</div>
<!--Course Logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>