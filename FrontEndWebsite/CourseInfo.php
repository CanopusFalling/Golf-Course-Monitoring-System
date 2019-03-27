<?php
//Only runs this if the user has a cookie deeing as this page shouldn't be accessable to anyone without this cookie.
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//sets up the database connection to the SQLite Database.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Creates the select statement that pulls the User ID tied to that session token.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	//Prepares the command to protect against injection.
	$statement = $PDO->prepare($Command);
	//Executes the command.
	$statement->execute();
	//Pulls all the data into $SessionResults from the query.
	$SessionResults = $statement->fetchAll();

	//Selects all the details about the user found by the previous statement.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	//Executes the command
	$GoodCookie = $statement->execute();
	//Checks that the comand executed successfully.
	if($GoodCookie){
		//Catagories all of the data from the query into variables.
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

		//Preparing the statement above.
		$TokenStatement = $PDO->prepare($TokenQuery);
		//Executes the statement.
		$TokenStatement->execute();
		//Gets all the results and puts them into $TokenQueryResults.
		$TokenQueryResults = $TokenStatement->fetchAll();
	}else{
		//If the user doesn't have the valid cookie they are logged out and redirected to the index page.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Ensures no more of the page can load.
		die();
	}
}else{
	//If the user doesn't have the valid cookie they are redirected to the index page.
	header("Location: Index.php");
	//Ensures no more of the page can load.
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--Link to all the external scripts and styles-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<Script src="CourseMapInfoUpdater.js"></Script>
</head>
<body>

<!--Background frame animation-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation bar code.-->
<Nav class="Navigation">
	<li class="TopBlock" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	
	<?php
	//Shows the default loggin signup if the user doesn't have a cookie.
	if(empty($_COOKIE["BedAndCountySessionToken"])){
		echo"
		<li class='Login Block' onclick='window.location.href = \"Login.php\"'>Login</li>
		<li class='Login Block' onclick='window.location.href = \"SignIn.php\"'>Sign Up</li>
		";
	}else{
		try{
			//Tries to show the users name if it can.
			echo "
			<li class='Login Block' onclick='window.location.href = \"UserHome.php\"'>" .  $FirstName . " " . $SecondName . "</li>
			<li class='Login Block' onclick='document.cookie = \"BedAndCountySessionToken=0\"; window.location.href = \"Index.php\"'>Log Out</li>
			";
		}catch(Exception $e){
			//resorts to standard login signup if it can't do this.
			echo"
			<li class='Login Block' onclick='window.location.href = \"Login.php\"'>Login</li>
			<li class='Login Block' onclick='window.location.href = \"SignIn.php\"'>Sign Up</li>
			";
		}
	}
	
	?>
</Nav>

<!--This is where all of the user info is placed by the ajax script.-->
<div class="FullPannelSpacer">
<div class="FullPannel">

<div id="InsertDiv"></div>

</div>
</div>

</body>
<!--Course logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</html>


