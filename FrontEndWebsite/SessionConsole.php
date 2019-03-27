<?php
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//Database connection
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Queries the database to find the user id  of the user with that session token.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();
	
	//Gets all the details about the user from the database.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		//If the token is valid then the data is all seperated out into variables.
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
		
		//Executes the query to find the user's permissions.
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Checks that the user has the detailed map view permission.
		$DetailedMapView = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "DetailedMapView"){
				$DetailedMapView = true;
			}
		}
		
	}else{
		//If the token is invalid then it is cleared and then they are returned to the homepage.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Prevents the rest from loading
		die();
	}
}else{
	//If they don't have a token then they are returned to the homepage and the token is cleared to be safe.
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
	//Prevents the rest from loading
	die();
}

//If the user doens't have perms to be here then they get kicked back to the homepage.
if(!$DetailedMapView){
	header("Location: Index.php");
	//Prevents the rest from loading
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External code references.-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Background frame cycling divisions.-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation bar at the top of the site.-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="Login Block" onclick="window.location.href = 'UserHome.php'"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

<!--Full pannel spacer for the table of all the sessions.-->
<div class="FullPannelSpacer">
<div class="FullPannel">
<!--Button for adding new sessions.-->
<button class="ButtonLargeText" onclick="window.location.href = 'CreateSession.php'">Create Session</button>
<table id="Accounts">
	<tr>
		<!--Table headders-->
		<th>UserName</th>
		<th>Time Out</th>
		<th>Time In</th>
		<th>Collection Comment</th>
	</tr>
	<?php
	//Queries to find all of the user sessions and order them by date.
	$PhoneBookingsQuery = "SELECT PhoneBookings.BookingID, UserAccounts.UserName, PhoneBookings.DateTimeOut, PhoneBookings.DateTimeIn, PhoneBookings.CollectionComment FROM PhoneBookings
	INNER JOIN UserAccounts ON PhoneBookings.UserID = UserAccounts.UserID
	ORDER BY DateTimeOut DESC;";
	$UserQuery = $PDO -> prepare($PhoneBookingsQuery);
	$UserQuery -> execute();
	$Users = $UserQuery->fetchAll();
	//Prints out all of the results into the table and formats them for HTML.
	foreach($Users as $User){
		if($User["DateTimeIn"] == null){
			echo "<tr onclick=\"window.location.href = 'CloseSession.php?BookingID=" . $User[0] . "'\">";
		}else{
			echo "<tr>";
		}
		echo "<td>" . $User["UserName"] . "</td>";
		echo "<td>" . $User["DateTimeOut"] . "</td>";
		echo "<td>" . $User["DateTimeIn"] . "</td>";
		echo "<td>" . $User["CollectionComment"] . "</td>";
		echo "</tr>";
	}
	?>
</table>
</div>
</div>
<!--Course Logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>