<?php
//Connection definition of database for potential use later.
//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

$ErrorMessage = "";
$SuccessMessage = "";

if(!empty($_POST)){
	$Email = $_POST["Email"];
	$Password = $_POST["Password"];
	$Command = "SELECT PasswordHash FROM UserAccounts WHERE Email = '" . $Email . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$results = $statement->fetchAll();
	if(password_verify ($Password , $results[0][0])){
		$SuccessMessage = "LoggedIn";
		
		$Token = "";
		$Characters = range('a','z');
		for($i = 0; $i < 50; $i++){
			$Num = mt_rand(0, 25);
			$Token .= $Characters[$Num];
		}
		
		$date = date('m-d-Y H:i:s', time());
		$Command = "INSERT INTO UserSessions (SessionToken, DateIssued) VALUES ('" . $Token . "', '" . $date . "');";
		$PDO->query($Command);
		
		setcookie("BedAndCountySessionToken", $Token, time() + (86400 * 30), "/");
		
		header("Location: UserHome.php");
		die();
	}else{
		$ErrorMessage = "Invalid Credentials, Please Try Again.";
	}
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
	<li class="TopLogin" onclick="window.location.href = 'Login.php'">Login</li>
	<li class="Login Block" onclick="window.location.href = 'SignIn.php'">Sign Up</li>
</Nav>

<div class="SpacerDiv">
<form class="DetailsForm" method="post">

Email:<br>
<input class="LoginInput" type="text" name="Email" required><br>

Password:<br>
<input class="LoginInput" type="password" name="Password" required><br>

<Button class="FormButton" type="submit">Log In</Button>

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
</html>