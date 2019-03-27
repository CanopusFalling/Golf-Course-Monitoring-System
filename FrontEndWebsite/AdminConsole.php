<?php
//Checks if the cookie for the authentication contains a value to see if the user is logged in to the system.
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//Makes a new PDO connection to the sqlite database using it's location.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Check that the cookie is still valid for the session.
	//Preparing the command that selects the entry that matches the cookie from the database from the table.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	//Execute the command and return all the results into $SessionResults.
	$statement->execute();
	$SessionResults = $statement->fetchAll();
	
	//Finds the ID of the user tied to that session token.
	//Prepares the statement used to query the database.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	//Returns the entry that matches the ID of the cookie.
	$GoodCookie = $statement->execute();
	//Checks that the entry still has something in it.
	if($GoodCookie){
		//Sets up all of the data needed later on.
		$UserResults = $statement->fetchAll();
		$UserID = $UserResults[0][0];
		$UserName = $UserResults[0][1];
		$Email = $UserResults[0][2];
		$FirstName = $UserResults[0][3];
		$SecondName = $UserResults[0][4];
		$DateOfBirth = $UserResults[0][5];
		$Password = $UserResults[0][5];

		//query to select all of the permissions tied to the user.
		$TokenQuery = "SELECT PermissionName FROM UserSessions 
		INNER JOIN UserAccounts ON UserSessions.UserID = UserAccounts.UserID
		INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
		INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
		INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
		INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
		WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
		//Prepared for use stopping SQL injection.
		$TokenStatement = $PDO->prepare($TokenQuery);
		//Executes command.
		$TokenStatement->execute();
		//Returns all of the permissions into $TokenQueryResults.
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//sets the account edditing to false by default.
		$AccountEditing = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "PermissionAssignment"){
				//Changes the account edditing to true if one of the permissions is "Permission Assignment"
				$AccountEditing = true;
			}
		}
		
	}else{
		//If the cookie doesn't line up with a user in the data base this is run.
		//Erases the cookie.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		//Sends them to the homepage.
		header("Location: Index.php");
		//ensure that no code past here can be run.
		die();
	}
}else{
	//Sends them to the homepage if they don't have a cookie.
	header("Location: Index.php");
	//ensure that no code past here can be run.
	die();
}

if(!$AccountEditing){
	//Sends them to the homepage if they don't have permission to edit accounts.
	header("Location: Index.php");
	//ensure that no code past here can be run.
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--Link to the ubuntu google font and the css stylesheet used for the website.-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
</head>
<body>

<!--Frame divs used to animate the frames throught the website assigned by class and not ID 
intentiaonally so as there can be multiple nested into one page and they will change in sync.-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--This is for the navigation bar at the top of the page the user should be 
logged in to be here so it is assumed that they will have details.-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class='TopBlock' onclick="window.location.href = 'AdminConsole.php'">Admin Console</li>
	<li class="Login Block" onclick="window.location.href = 'UserHome.php'"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

<!--This is the pannel section that the table is created within.-->
<div class="FullPannelSpacer">
<div class="FullPannel">
<table id="Accounts">
	<tr>
		<!--Hedders of the columns-->
		<th>UserName</th>
		<th>Email</th>
		<th>FirstName</th>
		<th>LastName</th>
		<th>Date Of Birth</th>
	</tr>
	<!-- Handles all of the entries into the table -->
	<?php
	//Prepares a query that selects all of the user accounts from the database and executes it.
	$UserQuery = $PDO -> prepare("SELECT * FROM UserAccounts;");
	$UserQuery -> execute();
	//Runs the query and returns all of the users into the array $Users.
	$Users = $UserQuery->fetchAll();
	foreach($Users as $User){
		//For each user this creates a new row in the table using HTML.
		//the link is for the user breakdown page so that when the admin clicks on the entry in the 
		//table they are sent to that page with the user ID of that user.
		echo "<tr onclick=\"window.location.href = 'UserBreakdown.php?UserID=" . $User[0] . "'\">";
		$Count = 0;
		//Fills in the relevant column for each of the pices of data by iterating through all of the data.
		foreach($User as $Item){
			if(($Count % 2) == 0 and $Count >= 2 and $Count < 12){
				echo "<td>" . $Item . "</td>";
			}
			$Count = $Count + 1;
		}
	}
	?>
</table>
</div>
</div>
<!--Course Logo for branding -->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>