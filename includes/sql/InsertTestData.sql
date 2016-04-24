INSERT INTO Groups (Name)
VALUES ('Admin');

INSERT INTO Groups (Name)
VALUES ('Test');

INSERT INTO Users (Name)
VALUES ('rschrader');

INSERT INTO Users (Name)
VALUES ('1234567');

INSERT INTO Users (Name)
VALUES ('0987654');

INSERT INTO GroupMembers(GroupID, UserID)
VALUES( (SELECT ID FROM Groups WHERE Groups.Name = 'Admin'), (SELECT ID FROM Users WHERE Users.Name = 'rschrader') );

INSERT INTO GroupMembers(GroupID, UserID)
VALUES( (SELECT ID FROM Groups WHERE Groups.Name = 'Test'), (SELECT ID FROM Users WHERE Users.Name = '1234567') );

INSERT INTO Permissions(Name)
VALUES('AdminPermission');

INSERT INTO Permissions(Name)
VALUES('TestRead');

INSERT INTO Permissions(Name)
VALUES('TestWrite');

INSERT INTO Permissions(Name)
VALUES('UserPermission');

INSERT INTO GroupPermissions(PermissionID, GroupID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'AdminPermission'), (SELECT ID FROM Groups WHERE Groups.Name = 'Admin') );

INSERT INTO GroupPermissions(PermissionID, GroupID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'TestRead'), (SELECT ID FROM Groups WHERE Groups.Name = 'Test') );

INSERT INTO GroupPermissions(PermissionID, GroupID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'TestWrite'), (SELECT ID FROM Groups WHERE Groups.Name = 'Test') );

INSERT INTO UserPermissions(PermissionID, UserID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'UserPermission'), (SELECT ID FROM Users WHERE Users.Name = '1234567') );

INSERT INTO UserPermissions(PermissionID, UserID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'UserPermission'), (SELECT ID FROM Users WHERE Users.Name = '0987654') );

INSERT INTO Files(Name)
VALUES('HtmlFile');

INSERT INTO Permissions(Name)
VALUES('HtmlRead');

INSERT INTO Permissions(Name)
VALUES('HtmlWrite');

INSERT INTO FilePermissions(PermissionID, FileID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'HtmlRead'), (SELECT ID FROM Files WHERE Files.Name = 'HtmlFile') );

INSERT INTO FilePermissions(PermissionID, FileID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'HtmlWrite'), (SELECT ID FROM Files WHERE Files.Name = 'HtmlFile') );

INSERT INTO UserPermissions(PermissionID, UserID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'HtmlRead'), (SELECT ID FROM Users WHERE Users.Name = '1234567') );

INSERT INTO UserPermissions(PermissionID, UserID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'HtmlWrite'), (SELECT ID FROM Users WHERE Users.Name = '1234567') );

INSERT INTO UserPermissions(PermissionID, UserID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'HtmlRead'), (SELECT ID FROM Users WHERE Users.Name = '0987654') );

INSERT INTO UserPermissions(PermissionID, UserID)
VALUES( (SELECT ID FROM Permissions WHERE Permissions.Name = 'HtmlWrite'), (SELECT ID FROM Users WHERE Users.Name = '0987654') );