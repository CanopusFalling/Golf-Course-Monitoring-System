<?php
$SuccessMessage = "";
$ErrorMessage = "";
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$SessionResults = $statement->fetchAll();

	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	$GoodCookie = $statement->execute();
	if($GoodCookie){
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

		$TokenStatement = $PDO->prepare($TokenQuery);
		$TokenStatement->execute();
		$TokenQueryResults = $TokenStatement->fetchAll();
		
		$DetailedMapView = false;
		foreach($TokenQueryResults as $Row){
			if($Row[0] == "DetailedMapView"){
				$DetailedMapView = true;
			}
		}
		
		if($DetailedMapView){
			if(!empty($_POST)){
				$time = time();
				$timeout = date('m-d-Y H:i:s', $time);
				$Email = $_POST['Email'];
				$Password = $_POST['Password'];
				$PhoneID = $_POST['PhoneID'];
				
				$UserSelectionCommand = "SELECT UserID, PasswordHash FROM UserAccounts WHERE Email = '" . strtolower($Email) . "';";
				$UserSelectionStatement = $PDO->prepare($UserSelectionCommand);
				$UserSelectionStatement->execute();
				$UserSelectionResults = $UserSelectionStatement->fetchAll();
				if(password_verify ($Password, $UserSelectionResults[0][1])){
					$SessionInsertStatement = "INSERT INTO PhoneBookings (UserID, PhoneID, DateTimeOut) VALUES (" . $UserSelectionResults[0][0] . ", " . $PhoneID . ", '" . $timeout . "')";
					if($PDO->query($SessionInsertStatement) == true){
						$SuccessMessage = "Created Session Sucessfully!";
						header("Location: SessionConsole.php");
					}else{
						$ErrorMessage = "Something went wrong. :(";
					}
				}else{
					$ErrorMessage = "Invalid Email or Password!";
				}
			}		
		}
		
	}else{
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
	}
}else{
	setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
	header("Location: Index.php");
}

if(!$DetailedMapView){
	header("Location: Index.php");
	die();
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="Login Block" onclick="window.location.href = 'UserHome.php'"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>
<div class="SpacerDiv">
<form class="DetailsForm" method="post">
<div class="Mandatory-Star">*</div>
Email:<br>
<input class="LoginInput" type="text" name="Email" required><br>

<div class="Mandatory-Star">*</div>
Password:<br>
<input class="LoginInput" type="password" name="Password" required><br>

<div class="Mandatory-Star">*</div>
Phone ID:<br>
<input class="LoginInput" type="number" name="PhoneID" value="" required><br>

<Button class="FormButton" type="submit">Create Session</Button>

<?php
$Class = "";
if($ErrorMessage !== ""){$Class = "Error";}
echo "<div class='" . $Class . "'>" . $ErrorMessage; ?></div>

<?php
$Class = "";
if($SuccessMessage !== ""){$Class = "Success";}
echo "<div class='" . $Class . "'>" . $SuccessMessage; ?></div>

</form>
</div>
</body>
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</html>