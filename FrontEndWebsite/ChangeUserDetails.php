<?php
$ErrorMessage = "";
$SuccessMessage = "";
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
		
		if(!empty($_POST)){
			$FirstName = $_POST['FirstName'];
			$LastName = $_POST['LastName'];
			$Password = $_POST['Password'];
			$RepeatPassword = $_POST['RepeatPassword'];
			$Hash = password_hash($Password, PASSWORD_DEFAULT);
			$UserName = $_POST['UserName'];
			$Email = $_POST['Email'];
			$DateOfBirth = $_POST['DateOfBirth'];
			$Command = "UPDATE UserAccounts SET UserName = '" . $UserName . "', Email = '" . strtolower($Email) . "', FirstName = '" . $FirstName . "', LastName = '" . $LastName . "', DateOfBirth = '" . $DateOfBirth . "', PasswordHash = '" . $Hash . "' WHERE UserID = " . $UserID . ";";
			if($UserName !== "" || $Password !== "" || $RepeatPassword !== "" || $Email !== "" || $DateOfBirth !== ""){
				if($Password == $RepeatPassword){
					if(filter_var($Email, FILTER_VALIDATE_EMAIL)){
						if($PDO->query($Command) == true){
							$SuccessMessage = "Account Modified Succesfully";
						}else{
							$ErrorMessage = "Email Is Already In Use, Please Proceed To Reset Password on that Account.";
						}
					}else{
						$ErrorMessage = "Please Enter A Valid Email Format.";
					}
				}else{
					$ErrorMessage = "New Password And Repeated New Password Do Not Match, Please Re-Check Them And Continue.";
				}
			}else{
				$ErrorMessage = "Please Fill In All The Fields Marked With A Star(*).";
			}
		}
		
	}else{
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
	}
}else{
	header("Location: Index.php");
}
?>
<Head>
<div id="CodeRefs">
<link rel="stylesheet" href="Styles.css">
<Script src="CourseMapLocationUpdater.js"></Script>
</div>

<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="TopLogin"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

</Head>
<body>
<div class="SpacerDiv">
<form class="DetailsForm" method="post">
<div class="Mandatory-Star">*</div>
User Name:<br>
<input class="LoginInput" type="text" name="UserName" value="<?php echo $UserName ?>" required><br>

<div class="Mandatory-Star"></div>
First Name:<br>
<input class="LoginInput" type="text" name="FirstName" value="<?php echo $FirstName ?>"><br>

<div class="Mandatory-Star"></div>
Last Name:<br>
<input class="LoginInput" type="text" name="LastName" value="<?php echo $SecondName ?>"><br>

<div class="Mandatory-Star">*</div>
New Password:<br>
<input class="LoginInput" type="password" name="Password" value="" required><br>

<div class="Mandatory-Star">*</div>
Repeat New Password:<br>
<input class="LoginInput" type="password" name="RepeatPassword" value="" required><br>

<div class="Mandatory-Star">*</div>
Email:<br>
<input class="LoginInput" type="email" name="Email" value="<?php echo $Email ?>" required><br>

<div class="Mandatory-Star">*</div>
Date of Birth:<br>
<input class="LoginInput" type="Date" name="DateOfBirth" value="<?php echo $DateOfBirth ?>" required><br>

<Button class="FormButton" type="submit">Change Details</Button>

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
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>