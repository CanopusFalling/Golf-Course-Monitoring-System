<?php
//Connection definition of database for potential use later.
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

//Sets up the error and success strings for later.
$ErrorMessage = "";
$SuccessMessage = "";

if(!empty($_GET['NewUser'])){
	$SuccessMessage = "Account Created, Please Log in to Continue.";
}
if(!empty($_POST)){
	//If the post isn't empty then it gets all of the post values.
	$Email = $_POST["Email"];
	$Password = $_POST["Password"];
	//SQL command to verify the user's password.
	$Command = "SELECT UserID, PasswordHash FROM UserAccounts WHERE Email = '" . strtolower($Email) . "';";
	$statement = $PDO->prepare($Command);
	$statement->execute();
	$results = $statement->fetchAll();
	//If the password hashes match then the user proceeds from here.
	if(password_verify ($Password , $results[0][1])){
		//This makes the success message so that they know they have logged in.
		$SuccessMessage = "LoggedIn";
		
		//Generates the user their token.
		$Token = "";
		$Characters = range('a','z');
		for($i = 0; $i < 50; $i++){
			$Num = mt_rand(0, 25);
			$Token .= $Characters[$Num];
		}
		
		//Stores the token in the database with the time created so there's a record of loggins.
		$date = date('m-d-Y H:i:s', time());
		$Command = "INSERT INTO UserSessions (SessionToken, DateIssued, UserID) VALUES ('" . $Token . "', '" . $date . "', " . $results[0][0] . ");";
		$PDO->query($Command);
		
		//Sets their token to a cookie and sends them to the user home.
		setcookie("BedAndCountySessionToken", $Token, time() + (60 * 60), "/");
		header("Location: UserHome.php");
		//kills the rest of the page.
		die();
	}else{
		//Tells the user their credentials are invalid.
		$ErrorMessage = "Invalid Credentials, Please Try Again.";
	}
}

?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External refs to code-->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Background cycler divs-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Top navigation bar div-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="TopLogin" onclick="window.location.href = 'Login.php'">Login</li>
	<li class="Login Block" onclick="window.location.href = 'SignIn.php'">Sign Up</li>
</Nav>

<!--Spacer divs and container divs for the form.-->
<div class="SpacerDiv">
<form class="DetailsForm" method="post">

Email:<br>
<input class="LoginInput" type="email" name="Email" required><br>

Password:<br>
<input class="LoginInput" type="password" name="Password" required><br>

<!--Submit button.-->
<Button class="FormButton" type="submit">Log In</Button>

<?php
//Displays an error message if something goes wrong.
$Class = "";
if($ErrorMessage !== ""){$Class = "Error";}
echo "<div class='" . $Class . "'>" . $ErrorMessage; ?></div>

<?php
//Shows the success message.
$Class = "";
if($SuccessMessage !== ""){$Class = "Success";}
echo "<div class='" . $Class . "'>" . $SuccessMessage; ?></div>

</form>
</div>
<!--Course logo-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>