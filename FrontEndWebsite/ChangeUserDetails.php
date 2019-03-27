<?php
//Defines all of the error and sucess messages so that they can be edited at any point.
$ErrorMessage = "";
$SuccessMessage = "";
if(!empty($_COOKIE["BedAndCountySessionToken"])){
	//sets up the database connection using PDO
	$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');
	
	//Queries to check that the cookie is still valid when coming from the user home.
	//Sets up the database query and prepares it.
	$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
	$statement = $PDO->prepare($Command);
	//Executes the query
	$statement->execute();
	//Returns the results into $SessionResults
	$SessionResults = $statement->fetchAll();
	
	//If the cookie is valid then it tries to select the user to get their details.
	//Sets up and prepares the SQL statement.
	$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
	$statement = $PDO->prepare($Command0);
	//Executes the command.
	$GoodCookie = $statement->execute();
	//If the SQL returns a valid answer then all of the results are catagorised into variables.
	if($GoodCookie){
		$UserResults = $statement->fetchAll();
		$UserID = $UserResults[0][0];
		$UserName = $UserResults[0][1];
		$Email = $UserResults[0][2];
		$FirstName = $UserResults[0][3];
		$SecondName = $UserResults[0][4];
		$DateOfBirth = $UserResults[0][5];
		$Password = $UserResults[0][5];
		
		//Will only run if the form has some values in it not nescesery seeing as the user 
		//would have to edit the HTML to cause this but usefull just in case.
		if(!empty($_POST)){
			//Gets all of the new data that the user has input and catagorises it into different variables.
			$FirstName = $_POST['FirstName'];
			$LastName = $_POST['LastName'];
			$Password = $_POST['Password'];
			$RepeatPassword = $_POST['RepeatPassword'];
			$Hash = password_hash($Password, PASSWORD_DEFAULT);
			$UserName = $_POST['UserName'];
			$Email = $_POST['Email'];
			$DateOfBirth = $_POST['DateOfBirth'];
			//Writes the update comand for the SQL.
			$Command = "UPDATE UserAccounts SET UserName = '" . $UserName . "', Email = '" . strtolower($Email) . "', FirstName = '" . $FirstName . "', LastName = '" . $LastName . "', DateOfBirth = '" . $DateOfBirth . "', PasswordHash = '" . $Hash . "' WHERE UserID = " . $UserID . ";";
			//Checks that all of the fields aren't blank.
			if($UserName !== "" || $Password !== "" || $RepeatPassword !== "" || $Email !== "" || $DateOfBirth !== ""){
				//Checking that the password and it's repeat are the same.
				if($Password == $RepeatPassword){
					//Validates the email format.
					if(filter_var($Email, FILTER_VALIDATE_EMAIL)){
						//Checks that the command runs in the database.
						if($PDO->query($Command) == true){
							$SuccessMessage = "Account Modified Succesfully";
						}else{
							//Assumes that the email is in use if the query fails.
							$ErrorMessage = "Email Is Already In Use, Please Proceed To Reset Password on that Account.";
						}
					}else{
						//If the format of the email is wrong.
						$ErrorMessage = "Please Enter A Valid Email Format.";
					}
				}else{
					//For if the repeated password values don't match.
					$ErrorMessage = "New Password And Repeated New Password Do Not Match, Please Re-Check Them And Continue.";
				}
			}else{
				//If one of the fields isn't filled in.
				$ErrorMessage = "Please Fill In All The Fields Marked With A Star(*).";
			}
		}
		//If the post is empty then the page will just refresh.
		
	}else{
		//If the cookie isn't valid the fake cookie is overwritten and then the 
		//user is sent to the homepage.
		setcookie("BedAndCountySessionToken", null, time() + (86400 * 30), "/");
		header("Location: Index.php");
		//Stops the rest of the page from loading.
		die();
	}
}else{
	//Sends the user to the homepage if they don't have the cookie.
	header("Location: Index.php");
}
?>
<Head>
<div id="CodeRefs">
<!--Stylesheet ref-->
<link rel="stylesheet" href="Styles.css">
</div>

<!--Frame divs used to animate the frames throught the website assigned by class and not ID 
intentiaonally so as there can be multiple nested into one page and they will change in sync.-->
<div class="Frame1"></div>
<div class="Frame2"></div>
<div class="Frame3"></div>
<div class="Frame4"></div>

<!--The navigation bar for the website.-->
<Nav class="Navigation">
	<li class="Block" onclick="window.location.href = 'Index.php'">Home</li>
	<li class="Block" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	<li class="TopLogin"><?php echo $FirstName . " " . $SecondName;?></li>
	<li class="Login Block" onclick="document.cookie = 'BedAndCountySessionToken=0'; window.location.href = 'index.php'">Log Out</li>
</Nav>

</Head>
<body>
<!--Main division that the form is all contained inside of.-->
<div class="SpacerDiv">
<form class="DetailsForm" method="post">
<!--This is the mandatory star for all of the fields that are required.-->
<div class="Mandatory-Star">*</div>
User Name:<br>
<!--All of the fields are pre filled by php with the user's current details.-->
<input class="LoginInput" type="text" name="UserName" value="<?php echo $UserName ?>" required><br>

<div class="Mandatory-Star"></div>
First Name:<br>
<input class="LoginInput" type="text" name="FirstName" value="<?php echo $FirstName ?>"><br>

<div class="Mandatory-Star"></div>
Last Name:<br>
<input class="LoginInput" type="text" name="LastName" value="<?php echo $SecondName ?>"><br>

<div class="Mandatory-Star">*</div>
New Password:<br>
<!--Passoword field isn't filled as the database doesn't know what the password is.-->
<input class="LoginInput" type="password" name="Password" value="" required><br>

<div class="Mandatory-Star">*</div>
Repeat New Password:<br>
<!--Repeat so the user has more chance of getting it right.-->
<input class="LoginInput" type="password" name="RepeatPassword" value="" required><br>

<div class="Mandatory-Star">*</div>
Email:<br>
<input class="LoginInput" type="email" name="Email" value="<?php echo $Email ?>" required><br>

<div class="Mandatory-Star">*</div>
Date of Birth:<br>
<!--This field is set up with a little date selector to stop people getting it wrong.-->
<input class="LoginInput" type="Date" name="DateOfBirth" value="<?php echo $DateOfBirth ?>" required><br>

<!--The button of the form that initiates the change.-->
<Button class="FormButton" type="submit">Change Details</Button>

<?php
//Displays an error message if the $ErrorMessage isn't empty.
$Class = "";
if($ErrorMessage !== ""){$Class = "Error";}
echo "<div class='" . $Class . "'>" . $ErrorMessage; ?></div>

<?php
//Displays a success message if the $SuccessMessage isn't empty.
$Class = "";
if($SuccessMessage !== ""){$Class = "Success";}
echo "<div class='" . $Class . "'>" . $SuccessMessage; ?></div>

</form>
</div>
<!--Logo in the bottom right for branding.-->
<img src="ImageGallery/bedfordcountylogo.jpg" class="CourseLogo"/>
</body>