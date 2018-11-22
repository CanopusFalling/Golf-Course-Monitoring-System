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
				
DROP TABLE PermissionGroupAllocation;
CREATE TABLE PermissionGroupAllocation(
                PermissionGroupAllocationID  INTEGER PRIMARY KEY,
                UserID INTEGER NOT NULL,
                PermissionGroupID INTEGER NOT NULL
				);
				
DROP TABLE PermissionGroups;
CREATE TABLE PermissionGroups(
                PermissionGroupID INTEGER PRIMARY KEY,
                PermissionGroupName VARCHAR(40) UNIQUE);

DROP TABLE PermissionAllocation;
CREATE TABLE PermissionAllocation(
                PermissionAllocationID INTEGER PRIMARY KEY,
                PermissionID INTEGER  NOT NULL,
                PermissionGroupID INTEGER NOT NULL 
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
                CollectionComment VARCHAR(1000)
				);

DROP TABLE Phone;
CREATE TABLE Phone(
                PhoneID INTEGER PRIMARY KEY,
                PhoneName VARCHAR(40) NOT NULL
				);
