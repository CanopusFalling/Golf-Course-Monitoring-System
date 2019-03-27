<?php
//Checks if the cookie exists.
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//defines the database connection.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Queries to find the userID that relates to the session token.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();
	
	//Queries to find the user that relates to the user ID.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		//Returns all of the details of the user from the database and then puts all the components into different variables.
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
		//Runs the query above to return all of the user's permissions.
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Checks if the permissions allow them to view course details.
		$AllowedToViewDetailed = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "DetailedMapView"){
				$AllowedToViewDetailed = true;
			}
		}
		if(!$AllowedToViewDetailed){
			//If they aren't this sends them to the homepage.
			header("Location: Index.php");
		}
	}else{
		//If the cookie is invalid this sends them back to the homepage and clears the cookie.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Stops the rest of the page loading.
		die();
	}
}else{
		//If the cookie doesn't exist this sends them back to the homepage and clears the cookie.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Stops the rest of the page loading.
		die();
}
?>
<Head>
<div id="CodeRefs">
<!--Code Refs-->
<link rel="stylesheet" href="Styles.css">
<Script src="UserViewLocationUpdater.js">
</Script>
</div>

<!--Frames for the animation-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation bar division-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="TopBlock" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	
	<?php
	if(empty($_COOKIE["BedAndCountySessionToken"])){
		echo"
		<li class='Login Block' onclick='window.location.href = \"Login.php\"'>Login</li>
		<li class='Login Block' onclick='window.location.href = \"SignIn.php\"'>Sign Up</li>
		";
	}else{
		echo "
		<li class='Login Block' onclick='window.location.href = \"UserHome.php\"'>" .  $FirstName . " " . $SecondName . "</li>
		<li class='Login Block' onclick='document.cookie = \"BedAndCountySessionToken=0\"; window.location.href = \"Index.php\"'>Log Out</li>
		";
	}
	
	?>
</Nav>
</Style>
</Head>
<body>
<!--Shows the text to the right of the map.-->
<div class="PannelSpacer">
<div class="Pannel">
<div class="PannelItem">
Viewing Position of Booking Session:
</div>
<div class="PannelItem" id="BookingID">
<!--Shows the user ID-->
<?php echo $_GET["BookingID"]; ?>
</div>
</div>
</div>
<!--Couses the map-->
<div id="Map">
<div class="Course-Image" style='float:left;'><img src="ImageGallery/CourseMap.png" alt="Course Map" width="800px" height="1300px"></div>

<!--Insert div for all of the posisiton of players.-->
<div id="InsertDiv"></div>
</div>
<!--Course Logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
