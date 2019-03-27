<?php
//Checks that the cookie isn't empty.
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//new database connection.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Runs the query that finds which user the token points to.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();
	
	//Gets all the user details.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		//Puts all the user results into an array and then splits that array down.
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
		//Runs the query above to find all the permissions tied to the user.
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Checks all of the permissions relevant to this site.
		$AccountEditing = false;
		$DetailedMapView = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "PermissionAssignment"){
				$AccountEditing = true;
			}else if($Row[0] == "DetailedMapView"){
				$DetailedMapView = true;
			}
		}
	}else{
		//If the cookie isn't valid the user is sent home and the cookie is erased.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
	}
}else{
	//If the cookie doesn't exist the user is sent home.
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External script refs-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Background frame animation-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation bar housing.-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<?php
	//Decides wether the user gets to see the admin console and the session console on their navigation bar.
	if($AccountEditing){
		echo"<li class='Block' onclick='window.location.href = \"AdminConsole.php\"'>Admin Console</li>";
	}
	if($DetailedMapView){
		echo"<li class='Block' onclick='window.location.href = \"SessionConsole.php\"'>Session Console</li>";
	}
	?>
	<!--Puts their name in the top right as well as a logout button that clears the token cookie and then sends them home.-->
	<li class="TopLogin"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

<!--All the user infromation is housed in this pannel.-->
<div class="PannelSpacer">
<div class="Pannel">
<div class="PannelItem">
Welcome <?php echo $FirstName . " " . $SecondName;?>
</div>
</div>
<div class="Pannel">
<div class="PannelItem">
UserName: <?php echo $UserName; ?>
</div>
<div class="PannelItem">
FirstName: <?php echo $FirstName; ?>
</div>
<div class="PannelItem">
LastName: <?php echo $SecondName; ?>
</div>
<div class="PannelItem">
Email: <?php echo $Email; ?>
</div>
<div class="PannelItem">
Date of Birth: <?php echo $DateOfBirth; ?>
</div>
<!--Button to change the user details.-->
<Button onclick="window.location.href = 'ChangeUserDetails.php'" class="ButtonLargeText">Change Details</Button>
</div>
</div>
<!--Course logo.-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>
