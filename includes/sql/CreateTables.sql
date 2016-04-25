-- Table for tracking files that use the permission system
CREATE TABLE Files
(
	ID INTEGER PRIMARY KEY,
	Name varchar(255) UNIQUE NOT NULL
);

-- Create index on file names
CREATE INDEX Files_Name_ind ON Files(Name);



-- Table containing all known permissions
CREATE TABLE Permissions
(
	ID INTEGER PRIMARY KEY,
	Name varchar(255) UNIQUE NOT NULL
);

-- Index on permission names
CREATE INDEX Permissions_Name_ind ON Permissions(Name);



-- Many-to-many table linking files with the permissions they use
CREATE TABLE FilePermissions
(
	PermissionID INTEGER,
	FileID INTEGER, 
	FOREIGN KEY(PermissionID) REFERENCES Permissions(ID) ON DELETE CASCADE,
	FOREIGN KEY(FileID) REFERENCES Files(ID) ON DELETE CASCADE,
	PRIMARY KEY(PermissionID, FileID)
);



-- Table containing all users registered with the system
CREATE TABLE Users
(
	ID INTEGER PRIMARY KEY,
	Name varchar(255) UNIQUE NOT NULL   -- username
);

-- Index on user names for easy searching
CREATE INDEX Users_Name_ind ON Users(Name);



-- Many-to-many table linking users with the permissions they have
CREATE TABLE UserPermissions
(
	PermissionID INTEGER,
	UserID INTEGER,
	FOREIGN KEY(PermissionID) REFERENCES Permissions(ID) ON DELETE CASCADE,
	FOREIGN KEY(UserID) REFERENCES Users(ID) ON DELETE CASCADE,
	PRIMARY KEY(PermissionID, UserID)
);



-- Table containing all user permission groups in the system
CREATE TABLE Groups
(
	ID INTEGER PRIMARY KEY,
	Name varchar(255) NOT NULL UNIQUE
);

-- Index on group names for easy searching
CREATE INDEX Groups_Name_ind on Groups(Name);



-- Many-to-many table linking permission groups with the permissions their
-- members have
CREATE TABLE GroupPermissions
(
	PermissionID INTEGER,
	GroupID INTEGER,
	FOREIGN KEY(PermissionID) REFERENCES Permissions ON DELETE CASCADE,
	FOREIGN KEY(GroupID) REFERENCES Groups(ID) ON DELETE CASCADE,
	PRIMARY KEY(PermissionID, GroupID)
);



-- Many-to-many table linking permission groups with the members they contain
CREATE TABLE GroupMembers
(
	GroupID INTEGER,
	UserID INTEGER,
	FOREIGN KEY(GroupID) REFERENCES Groups(ID) ON DELETE CASCADE,
	FOREIGN KEY(UserID) REFERENCES Users(ID) ON DELETE CASCADE,
	PRIMARY KEY(GroupID, UserID)
);


