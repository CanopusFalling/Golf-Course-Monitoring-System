DROP TABLE GPSData;
CREATE TABLE GPSData(
                UserID INTEGER,
                DateTimeStamp DATETIME NOT NULL,
                Longitude DECIMAL NOT NULL,
                Latitude DECIMAL NOT NULL,
				FOREIGN KEY (UserID) REFERENCES UserAccounts(UserID)
				);
				
DROP TABLE UserAccounts;
CREATE TABLE UserAccounts(
                UserID INTEGER PRIMARY KEY,
                UserName VARCHAR(40) NOT NULL,
                Email VARCHAR(100) NOT NULL UNIQUE,
				FirstName VARCHAR(40),
				LastName VARCHAR(40),
				DateOfBirth DATE,
                PasswordHash CHAR(250) NOT NULL
				);
				
DROP TABLE UserSessions;
CREATE TABLE UserSessions(
                SessionID INTEGER PRIMARY KEY,
                SessionToken CHAR(50) NOT NULL,
				DateIssued DATETIME NOT NULL,
				UserID INTEGER NOT NULL,
				FOREIGN KEY (UserID) REFERENCES UserAccounts(UserID)
				);
				
DROP TABLE PermissionGroupAllocation;
CREATE TABLE PermissionGroupAllocation(
				PermissionGroupAllocationID  INTEGER PRIMARY KEY,
				UserID INTEGER NOT NULL,
				PermissionGroupID INTEGER NOT NULL,
				FOREIGN KEY (UserID) REFERENCES UserAccounts(UserID),
				FOREIGN KEY (PermissionGroupID) REFERENCES PermissionGroups(PermissionGroupID)
				);
				
DROP TABLE PermissionGroups;
CREATE TABLE PermissionGroups(
                PermissionGroupID INTEGER PRIMARY KEY,
                PermissionGroupName VARCHAR(40) UNIQUE
				);

DROP TABLE PermissionAllocation;
CREATE TABLE PermissionAllocation(
                PermissionAllocationID INTEGER PRIMARY KEY,
                PermissionID INTEGER NOT NULL,
                PermissionGroupID INTEGER NOT NULL,
				FOREIGN KEY (PermissionGroupID) REFERENCES PermissionGroups(PermissionGroupID),
				FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID)
				);

DROP TABLE Permissions;
CREATE TABLE Permissions(
                PermissionID INTEGER PRIMARY KEY,
                PermissionName VARCHAR(40)
				);

DROP TABLE PhoneBookings;
CREATE TABLE PhoneBookings(
                BookingID INTEGER PRIMARY KEY,
                UserID INTEGER NOT NULL,
                PhoneID INTEGER NOT NULL,
                DateTimeOut DATETIME NOT NULL,
                DateTimeIn DATETIME NOT NULL,
                CollectionComment VARCHAR(1000),
				FOREIGN KEY (UserID) REFERENCES UserAccounts(UserID),
				FOREIGN KEY (PhoneID) REFERENCES Phone(PhoneID)
				);

DROP TABLE Phone;
CREATE TABLE Phone(
                PhoneID INTEGER PRIMARY KEY,
                PhoneName VARCHAR(40) NOT NULL
				);

DELETE FROM Permissions;
INSERT INTO Permissions (PermissionName) VALUES
("PermissionAssignment"),
("CourseMapView"),
("DetailedMapView");

DELETE FROM PermissionGroups;
INSERT INTO PermissionGroups (PermissionGroupName) VALUES
("Admin"),
("CourseMonitor"),
("VerifiedUser");

DELETE FROM PermissionAllocation;
INSERT INTO PermissionAllocation (PermissionID, PermissionGroupID) VALUES
(1,1),(2,1),(3,1),(2,2),(3,2),(2,3);

DELETE FROM PermissionGroupAllocation;
INSERT INTO PermissionGroupAllocation (UserID, PermissionGroupID) VALUES
(1,1);

INSERT INTO UserAccounts (UserName, Email, FirstName, LastName, DateOfBirth, PasswordHash) VALUES
("DuplicateBotto", "Dupe1@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe2@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe3@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe4@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe5@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe6@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe7@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe8@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe9@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe10@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe11@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe12@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe13@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe14@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe15@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe16@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe17@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe18@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe19@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe20@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe21@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe22@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe23@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe24@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe25@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe26@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe27@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe28@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe29@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope"),
("DuplicateBotto", "Dupe30@Dupe.co.uk", "WhoKnows", "Again, Who Knows", CURRENT_TIMESTAMP, "Lol nope");
DELETE FROM UserAccounts WHERE UserName = "DuplicateBotto";
