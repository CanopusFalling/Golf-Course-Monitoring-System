<?php
//Sets up the error and sucess messages.
$SuccessMessage = "";
$ErrorMessage = "";
//Runs if the cookie exists.
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//Cretaes a new datavase connection.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Creates the select for the user that the cookie is attached to.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	//Executes the command.
	$statement->execute();
	//Retrives the result into $SessionResults.
	$SessionResults = $statement->fetchAll();

	//SQL that finds all the user details about that user from the previous query.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	//Prepared to avoid injection.
	$statement = $PDO->prepare($Command0);
	//Executes query amd gets a boolean to see if it ran.
	$GoodCookie = $statement->execute();
	//Checks if the results are returned
	if($GoodCookie){
		//Fetches all of the details about the user and then catagorises all of them into 
		//variables to make them easier to access later on in the code.
		$UserResults = $statement->fetchAll();
		$UserID = $UserResults[0][0];
		$UserName = $UserResults[0][1];
		$Email = $UserResults[0][2];
		$FirstName = $UserResults[0][3];
		$SecondName = $UserResults[0][4];
		$DateOfBirth = $UserResults[0][5];
		$Password = $UserResults[0][5];

		//Verifying Permission is valid using the following query.
		$TokenQuery = "SELECT PermissionName FROM UserSessions 
		INNER JOIN UserAccounts ON UserSessions.UserID = UserAccounts.UserID
		INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
		INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
		INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
		INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
		WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
		//Query is prepared to avoid injection.
		$TokenStatement = $PDO->prepare($TokenQuery);
		//Query is executed.
		$TokenStatement->execute();
		//Results pushed into $TokenQueryResults.
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Checks from the result that the user has the permission DetailedMapView.
		$DetailedMapView = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "DetailedMapView"){
				$DetailedMapView = true;
			}
		}
		
		//If they have the permission this is the part where the session is closed
		if($DetailedMapView){
			if(!empty($_POST)){
				//Gets the time 
				$time = time();
				//Formats the time into an SQL acceptable format.
				$timein = date('m-d-Y H:i:s', $time);
				//Gets all the comments that the user added.
				$Comment = $_POST['Comment'];
				$BookingID = $_POST['BookingID'];
				
				//Creates the modify statement to close the session.
				$ModifyStatement = "UPDATE PhoneBookings SET DateTimeIn = '" . $timein . "',CollectionComment = '" . $Comment . "' WHERE BookingID = " . $BookingID . ";";
				if($PDO->query($ModifyStatement)){
					//If the statement works sends the user to the session console.
					header("Location: SessionConsole.php");
				}
			}
		}
		
	}else{
		//If the user doesn't have the valid cookie they ae logged out and redirected to the index page.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Ensures no more of the page can load.
		die();
	}
}else{
	//If the cookie isn't there this is where they are sent back to the index and the cookie is cleared.
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
}

if(!$DetailedMapView){
	//If the user doesn't have the valid permission they are redirected to the index page.
	header("Location: Index.php");
	//ensures no more of the page loads.
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--Adds the scripts and the google fonts.-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
</head>
<body>

<!--Frames for the background animation divs so that they can be put into here.-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation bar for the website-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="Login Block" onclick="window.location.href = 'UserHome.php'"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

<!--Main form and content of the website starts here.-->
<div class="SpacerDiv">
<form class="DetailsForm" method="post">

<div class="Mandatory-Star">*</div>
<!--Automatically fills in the booking ID field and doesn't allow the user to change the field.-->
BookingID:<br>
<input class="LoginInput" type="number" name="BookingID" value=<?php echo $_GET['BookingID'] ?> required readonly><br>

<div class="Mandatory-Star">*</div>
Comment:<br>
<!--Comment field for the user to leave a comment about the phone's condition.-->
<input class="LoginInput" type="text" name="Comment" required><br>

<Button class="FormButton" type="submit">EndSession</Button>

<?php
$Class = "";
//Echos the error message if the variable $ErrorMessage has stuff in it.
if($ErrorMessage !== ""){$Class = "Error";}
echo "<div class='" . $Class . "'>" . $ErrorMessage; ?></div>

<?php
$Class = "";
//Echos the success message if the variable $SuccessMessage has stuff in it.
if($SuccessMessage !== ""){$Class = "Success";}
echo "<div class='" . $Class . "'>" . $SuccessMessage; ?></div>

</form>
</div>
</body>
<!--Course logo in the bottom right-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</html>