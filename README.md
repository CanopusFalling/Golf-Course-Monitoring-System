# Golf Course Monitoring System

This is an old project from my time in A level, not going to lie this is horrible but it's really funny to look at some of the code in this nightmare. Here's some funny examples.

## The Best SQL

```php
//Verifying Permission is valid.
$TokenQuery = "SELECT PermissionName FROM UserSessions 
INNER JOIN UserAccounts ON UserSessions.UserID = UserAccounts.UserID
INNER JOIN PermissionGroupAllocation ON UserAccounts.UserID = PermissionGroupAllocation.UserID
INNER JOIN PermissionGroups ON PermissionGroupAllocation.PermissionGroupID = PermissionGroups.PermissionGroupID
INNER JOIN PermissionAllocation ON PermissionGroups.PermissionGroupID = PermissionAllocation.PermissionGroupID
INNER JOIN Permissions ON Permissions.PermissionID = PermissionAllocation.PermissionID
WHERE SessionToken = '" . $_COOKIE["BedAndCountySessionToken"] . "';";
```

Honestly my approach to coding was basically to spam something like this out and pray, so thank fuck it worked first time.

## Cursed If Stack

```php
$Command = "INSERT INTO UserAccounts (UserName, Email, FirstName, LastName, DateOfBirth, PasswordHash) VALUES ('" . $UserName . "', '" . strtolower($Email) . "', '" . $FirstName . "', '" . $LastName . "', '" . $DateOfBirth . "', '" . $Hash . "')";
	if($UserName !== "" || $Password !== "" || $RepeatPassword !== "" || $Email !== "" || $DateOfBirth !== ""){
		if($Password == $RepeatPassword){
			if(filter_var($Email, FILTER_VALIDATE_EMAIL)){
				if($PDO->query($Command) == true){
```

Honestly this is some of the most cursed code ever, this segment is specifically from `FrontEndWebsite/SignIn.php`.

## Constants? What's a Constants?

```php
//Gets the time and take away 200 seconds.
$time = time();
$timeMin = $time - 200;
$date = date('m-d-Y H:i:s', $timeMin);
//Querys the database to get all of the GPS recording for the last 200 seconds.
$Query = "SELECT * FROM GPSData
INNER JOIN Phone ON GPSData.PhoneID = Phone.PhoneID 
INNER JOIN PhoneBookings ON Phone.PhoneID = PhoneBookings.PhoneID 
INNER JOIN UserAccounts on PhoneBookings.UserID = UserAccounts.UserID 
WHERE PhoneBookings.BookingID = " . $_GET['BookingID'] . ";";
```

Got a fun selection of just some fun hardcoded time variables and some absolutely mental SQL again.

Hope you had fun reading these! :)
