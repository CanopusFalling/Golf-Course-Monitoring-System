<?php
//$PDO = new PDO('sqlite:/home/samkent/Documents/GolfCourseGPSManagementSystem/Database/GolfData.db');
$PDO = new PDO('sqlite:C:\Users\kent_\OneDrive\Documents\Project work\GolfCourseGPSManagementSystem\Database\GolfData.db');

$Command = "SELECT * FROM UserSessions WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
$statement = $PDO->prepare($Command);
$statement->execute();
$SessionResults = $statement->fetchAll();

$Command0 = "SELECT * FROM UserAccounts WHERE UserID = " . $SessionResults[0][3] . ";";
$statement = $PDO->prepare($Command0);
$statement->execute();
$UserResults = $statement->fetchAll();
$UserID = $UserResults[0][0];
$Username = $UserResults[0][1];
$Email = $UserResults[0][2];
$FirstName = $UserResults[0][3];
$SecondName = $UserResults[0][4];
$DateOfBirth = $UserResults[0][5];
$Password = $UserResults[0][5];
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
	<li class="TopBlock" onclick="window.location.href = 'CourseMap.php'">CourseMap</li>
	
	<?php
	if(empty($_COOKIE["BedAndCountySessionToken"])){
		echo"
		<li class='Login Block' onclick='window.location.href = 'Login.php''>Login</li>
		<li class='Login Block' onclick='window.location.href = 'SignIn.php''>Sign Up</li>
		";
	}else{
		echo "
		<li class='Login Block' onclick='window.location.href = \"UserHome.php\"'>" .  $FirstName . " " . $SecondName . "</li>
		";
	}
	
	?>
</Nav>
</Style>
</Head>
<body>

<div id="Map">
<div class="Course-Image"><img src="ImageGallery/BMSMap.png" alt="Course Map" (width="1300px" height="800px")></div>
<!--<div class="Course-Image"><img src="ImageGallery/CourseMap.png" alt="Course Map" width="1300px" height="800px"></div>-->

<div id="InsertDiv"></div>
</div>
</body>


