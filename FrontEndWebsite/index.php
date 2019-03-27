<?php
//Checks that the cookie exists.
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//New database connection.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Uses an SQL query to check the database for the user that is tied to the session token bieng used.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	//Executes the command.
	$statement->execute();
	//Retrives all the data.
	$SessionResults = $statement->fetchAll();
	
	//Find all the data baout the user ID from the last query.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	//Runs the query
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		//Gets all of the data about the user and files it into all the correct variables.
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
		
		//Gets all the info about the user's permissions.
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
	}else{
		//If the cookie is invalid it is reset.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	}
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External script references-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--Legacy from javascript background changer.-->
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Background frame slideshow divs-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation bar-->
<Nav class="Navigation">
	<li class="TopBlock" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	
	<?php
	//Decides on what goes in the top right depending upon if the user is logged in.
	if(empty($_COOKIE["BedAndCountySessionToken"])){
		echo"
		<li class='Login Block' onclick='window.location.href = \"Login.php\"'>Login</li>
		<li class='Login Block' onclick='window.location.href = \"SignIn.php\"'>Sign Up</li>
		";
	}else{
		try{
			//Inserts the user's name into the top right and a loggout button.
			echo "
			<li class='Login Block' onclick='window.location.href = \"UserHome.php\"'>" .  $FirstName . " " . $SecondName . "</li>
			<li class='Login Block' onclick='document.cookie = \"BedAndCountySessionToken=0\"; window.location.href = \"Index.php\"'>Log Out</li>
			";
		}catch(Exception $e){
			//In case of an error resorts to standard screen.
			echo"
			<li class='Login Block' onclick='window.location.href = \"Login.php\"'>Login</li>
			<li class='Login Block' onclick='window.location.href = \"SignIn.php\"'>Sign Up</li>
			";
		}
	}
	
	?>
</Nav>
<!--Title of the page is all here.-->
<div class="TitleText">
<div class="TitleTextBox">
<h1>Bedford And County</br>
Golf Course</h1>
</div>
</div>

<!--Course Logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>


