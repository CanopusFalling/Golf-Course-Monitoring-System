<?php
//Checks that the cookie for the admin exists and that the User ID exists in the URL
if(!empty($_COOKIE["BedAndCountySessionToken"]) or empty($_GET['UserID'])){
	//New database connection.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Selects the user id tied to the session token in the cookie.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();
	
	//Gets all the user's details based on the user ID from the previous query.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
		//fetches all of the data if the query returns any results and then splits all of those into seperate variables.
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
		
		//Runs the query to get all the permissions tied to the user.
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Checks if they are allowed to edit accounts.
		$AccountEditing = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "CourseMapView"){
				$AccountEditing = true;
			}
		}
		
		//Gets all the details of the user that is about to be deleted.
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
		//Sets the cookie to null and redirects the user to home if the cookie they have is invalid.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Ensures that the rest of the page can't load.
		die();
	}
}else{
	//If the user has either no cookie or the user ID isn't there then the user is sent back to the home and logged out.
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
	//Ensures that no more of the page loads.
	die();
}

if(!$AccountEditing){
	//Sends the user home if they dont have permission.
	header("Location: Index.php");
	//Stops the rest of the page from loading.
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External scripts referenced here.-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--Legacy script from when I attempted to try cycling the background with javascript.-->
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Background frame animation divs-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Where all of the delete user pannel lies.-->
<div class="FullPannelSpacer">
<div class="FullPannel">
<Button class="DeleteButton" onclick="window.location.href = 'HiddenDeletePage.php?UserID=<?php echo $_GET['UserID']; ?>'">
Pressing This Button Will Permanantly Delete The User "<?php echo $FocusUserName; ?>" Are You Sure?
</Button>
<!--The delete button.-->
<Button class="ButtonLargeText" onclick="window.location.href = 'AdminConsole.php'">
Back To Safety
</Button>
</div>
</div>
<!--Course Logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>