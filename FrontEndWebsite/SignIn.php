<?php
//Connection definition of database for potential use later.
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

//Error and sucess message for later use.
$ErrorMessage = "";
$SuccessMessage = "";

//Checks if the post data is empty.
if(!empty($_POST)){
	//If the post data isn't empty then the data is all put into easy to use variables.
	$FirstName = $_POST['FirstName'];
	$LastName = $_POST['LastName'];
	$Password = $_POST['Password'];
	$RepeatPassword = $_POST['RepeatPassword'];
	//Hashes the passowrd so that the system doens't hold onto it.
	$Hash = password_hash($Password, PASSWORD_DEFAULT);
	$UserName = $_POST['UserName'];
	$Email = $_POST['Email'];
	$DateOfBirth = $_POST['DateOfBirth'];
	//Prepares the first bit of the insert.
	$Command = "INSERT INTO UserAccounts (UserName, Email, FirstName, LastName, DateOfBirth, PasswordHash) VALUES ('" . $UserName . "', '" . strtolower($Email) . "', '" . $FirstName . "', '" . $LastName . "', '" . $DateOfBirth . "', '" . $Hash . "')";
	if($UserName !== "" || $Password !== "" || $RepeatPassword !== "" || $Email !== "" || $DateOfBirth !== ""){
		if($Password == $RepeatPassword){
			if(filter_var($Email, FILTER_VALIDATE_EMAIL)){
				if($PDO->query($Command) == true){
					//Creates account and then pushes the user to the loggin site.
					$SuccessMessage = "Account Created, Please Log In To The Account To Continue...";
					header("Location: Login.php?NewUser=1");
				}else{
					//If the email is already in use.
					$ErrorMessage = "Email Is Already In Use, Please Proceed To Reset Password.";
				}
			}else{
				//If the email format is wrong.
				$ErrorMessage = "Please Enter A Valid Email Format.";
			}
		}else{
			//If the repeated passwords don't match.
			$ErrorMessage = "Password And Repeated Password Do Not Match, Please Re-Check Them And Continue.";
		}
	}else{
		//If not all of the fields that are mandatory are filled in.
		$ErrorMessage = "Please Fill In All The Fields Marked With A Star(*).";
	}
}
?>

<html>
<head>
<title>Bedford And County Golf Course</title>
<!--External code refs -->
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
<!--<script src="BackgroundCycler.js"></script>-->
</head>
<body>

<!--Background cycler-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--Navigation bar is all run here.-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="Login Block" onclick="window.location.href = 'Login.php'">Login</li>
	<li class="TopLogin" onclick="window.location.href = 'SignIn.php'">Sign Up</li>
</Nav>

<!--Form is all in this division.-->
<div class="SpacerDiv">
<form class="DetailsForm" method="post">
<div class="Mandatory-Star">*</div>
User Name:<br>
<!--All of the fields except password ones allow for the value to be saves if the user submits and then the creation fails.-->
<input class="LoginInput" type="text" name="UserName" required <?php if(!empty($_POST)){echo "value='" . $UserName . "'";};?>><br>

<div class="Mandatory-Star"></div>
First Name:<br>
<input class="LoginInput" type="text" name="FirstName" <?php if(!empty($_POST)){echo "value='" . $FirstName . "'";};?>><br>

<div class="Mandatory-Star"></div>
Last Name:<br>
<input class="LoginInput" type="text" name="LastName" <?php if(!empty($_POST)){echo "value='" . $LastName . "'";};?>><br>

<div class="Mandatory-Star">*</div>
Password:<br>
<input class="LoginInput" type="password" name="Password" required><br>

<div class="Mandatory-Star">*</div>
Repeat Password:<br>
<input class="LoginInput" type="password" name="RepeatPassword" required><br>

<div class="Mandatory-Star">*</div>
Email:<br>
<input class="LoginInput" type="email" name="Email" required <?php if(!empty($_POST)){echo "value='" . $Email . "'";};?>><br>

<div class="Mandatory-Star">*</div>
Date of Birth:<br>
<input class="LoginInput" type="Date" name="DateOfBirth" required <?php if(!empty($_POST)){echo "value='" . $DateOfBirth . "'";};?>><br>

<Button class="FormButton" type="submit">Sign Up</Button>

<?php
//Error reporting to the user.
$Class = "";
if($ErrorMessage !== ""){$Class = "Error";}
echo "<div class='" . $Class . "'>" . $ErrorMessage; ?></div>

<?php
//Success messages.
$Class = "";
if($SuccessMessage !== ""){$Class = "Success";}
echo "<div class='" . $Class . "'>" . $SuccessMessage; ?></div>

</form>
</div>
<!--Course logo.-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>
</html>