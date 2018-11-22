<?php
//Connection definition of database for potential use later.
//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

$ErrorMessage = "";
$SuccessMessage = "";

if(!empty($_POST)){
	$FirstName = $_POST['FirstName'];
	$LastName = $_POST['LastName'];
	$Password = $_POST['Password'];
	$RepeatPassword = $_POST['RepeatPassword'];
	$Hash = password_hash($Password, PASSWORD_DEFAULT);
	$UserName = $_POST['UserName'];
	$Email = $_POST['Email'];
	$DateOfBirth = $_POST['DateOfBirth'];
	$Command = "INSERT INTO UserAccounts (UserName, Email, PasswordHash) VALUES ('" . $UserName . "', '" . $Email . "', '" . $Hash . "')";
	if($UserName !== "" || $Password !== "" || $RepeatPassword !== "" || $Email !== "" || $DateOfBirth !== ""){
		if($Password == $RepeatPassword){
			if(filter_var($Email, FILTER_VALIDATE_EMAIL)){
				if($PDO->query($Command) == true){
					$SuccessMessage = "Account Created, Please Log In To The Account To Continue...";
				}else{
					$ErrorMessage = "Email Is Already In Use, Please Proceed To Reset Password.";
				}
			}else{
				$ErrorMessage = "Please Enter A Valid Email Format.";
			}
		}else{
			$ErrorMessage = "Password And Repeated Password Do Not Match, Please Re-Check Them And Continue.";
		}
	}else{
		$ErrorMessage = "Please Fill In All The Fields Marked With A Star(*).";
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
	<li class="TopBlock" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="Login Block" onclick="window.location.href = 'Login.php'">Login</li>
	<li class="TopLogin" onclick="window.location.href = 'SignIn.php'">Sign Up</li>
</Nav>

<div class="SpacerDiv">
<form class="DetailsForm" method="post">
<div class="Mandatory-Star">*</div>
User Name:<br>
<input class="LoginInput" type="text" name="UserName"><br>

<div class="Mandatory-Star"></div>
First Name:<br>
<input class="LoginInput" type="text" name="FirstName"><br>

<div class="Mandatory-Star"></div>
Last Name:<br>
<input class="LoginInput" type="text" name="LastName"><br>

<div class="Mandatory-Star">*</div>
Password:<br>
<input class="LoginInput" type="password" name="Password" required><br>

<div class="Mandatory-Star">*</div>
Repeat Password:<br>
<input class="LoginInput" type="password" name="RepeatPassword" required><br>

<div class="Mandatory-Star">*</div>
Email:<br>
<input class="LoginInput" type="email" name="Email" required><br>

<div class="Mandatory-Star">*</div>
Date of Birth:<br>
<input class="LoginInput" type="Date" name="DateOfBirth" required><br>

<Button class="FormButton" type="submit">Sign Up</Button>

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