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
$UserName = $UserResults[0][1];
$Email = $UserResults[0][2];
$FirstName = $UserResults[0][3];
$SecondName = $UserResults[0][4];
$DateOfBirth = $UserResults[0][5];
$Password = $UserResults[0][5];
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
	<li class="TopLogin"><?php echo $FirstName . " " . $SecondName;?></li>
</Nav>

<div class="PannelSpacer">
<div class="Pannel">
<div class="PannelItem">
Welcome <?php echo $FirstName . " " . $SecondName;?>
</div>
<div class="PannelItem">
UserName: <?php echo $UserName; ?>
</div>
<div class="PannelItem">
FirstName: <?php echo $FirstName; ?>
</div>
<div class="PannelItem">
LastName: <?php echo $SecondName; ?>
</div>
<div class="PannelItem">
Email: <?php echo $Email; ?>
</div>
<div class="PannelItem">
Date of Birth: <?php echo $DateOfBirth; ?>
</div>
</div>
</div>
</body>
</html>
