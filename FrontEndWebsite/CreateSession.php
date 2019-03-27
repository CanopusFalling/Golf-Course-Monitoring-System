<?php
//Defines the success and faliure variables.
$SuccessMessage = "";
$ErrorMessage = "";
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//New database connection.
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	//Creates the sql command to get the user ID attached to the cookie and returns it.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();
	
	//Gets all of the users details based on the token that they have.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	//Only runs if the cookie is valid.
	if($GoodCookie){
		//Returns all the user details into an array and then splits it down into many variables.
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
		
		//Executes the above command with injection protection.
		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		//Checks that the user has the correct permissions to be on this site.
		$DetailedMapView = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "DetailedMapView"){
				$DetailedMapView = true;
			}
		}
		
		//runs if the user has the correct permissions.
		if($DetailedMapView){
			//Runs if the post is there.
			if(!empty($_POST)){
				//Gets the current time.
				$time = time();
				//Formatted for SQL
				$timeout = date('m-d-Y H:i:s', $time);
				//Puts form data into variables.
				$Email = $_POST['Email'];
				$Password = $_POST['Password'];
				$PhoneID = $_POST['PhoneID'];
				
				//Checks that the account is vaid and the hashes match.
				$UserSelectionCommand = "SELECT UserID, PasswordHash FROM UserAccounts WHERE Email = '" . strtolower($Email) . "';";
				$UserSelectionStatement = $PDO->prepare($UserSelectionCommand);
				$UserSelectionStatement->execute();
				$UserSelectionResults = $UserSelectionStatement->fetchAll();
				//If they match then the code to create the session is run.
				if(password_verify ($Password, $UserSelectionResults[0][1])){
					//Prepares the SQl statement.
					$SessionInsertStatement = "INSERT INTO PhoneBookings (UserID, PhoneID, DateTimeOut) VALUES (" . $UserSelectionResults[0][0] . ", " . $PhoneID . ", '" . $timeout . "')";
					if($PDO->query($SessionInsertStatement) == true){
						//Checks that the query runs.
						$SuccessMessage = "Created Session Sucessfully!";
						header("Location: SessionConsole.php");
					}else{
						//If something goes wrong with the database.
						$ErrorMessage = "Something went wrong. :(";
					}
				}else{
					//If the account name or password is wrong.
					$ErrorMessage = "Invalid Email or Password!";
				}
			}		
		}
		
	}else{
		//If the cookie is invalid then the user gets the cookie wiped and are sent back to the homepage.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Stops anything else from loading.
		die();
	}
}else{
	//if the user dosn't have a cookie then the cookie is wiped just in case and then they are redirected to the homepage.
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
	//stops anything past this loading.
	die();
}

if(!$DetailedMapView){
	//If the user doens't have the correct permissions they are sent back to the homepage.
	header("Location: Index.php");
	//Stops anythig past this from loading.
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External scripts.-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Frmaes for the background slideshow animation.-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--The navigation bar for the course website is all handles in this div.-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="Login Block" onclick="window.location.href = 'UserHome.php'"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

<!--The form is all contained within this div.-->
<div class="SpacerDiv">
<form class="DetailsForm" method="post">
<div class="Mandatory-Star">*</div>
Email:<br>
<!--All of the fields are all set up with validation checking on the HTML side as well as the PHP.-->
<input class="LoginInput" type="text" name="Email" required><br>

<div class="Mandatory-Star">*</div>
Password:<br>
<input class="LoginInput" type="password" name="Password" required><br>

<div class="Mandatory-Star">*</div>
Phone ID:<br>
<!--Numbers box so that the user gets an up and down arrow in the form.-->
<input class="LoginInput" type="number" name="PhoneID" value="" required><br>

<!--Creates the session when the button is pressed.-->
<Button class="FormButton" type="submit">Create Session</Button>

<?php
//Runs if the error message is there
$Class = "";
if($ErrorMessage !== ""){$Class = "Error";}
echo "<div class='" . $Class . "'>" . $ErrorMessage; ?></div>

<?php
//Shows the sucess message if there is one.
$Class = "";
if($SuccessMessage !== ""){$Class = "Success";}
echo "<div class='" . $Class . "'>" . $SuccessMessage; ?></div>

</form>
</div>
</body>
<!--Course logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</html>