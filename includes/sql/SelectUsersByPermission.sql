--Select all users that have a permission
SELECT Users.Name
FROM Users
INNER JOIN UserPermissions
ON UserPermissions.UserID = Users.ID
INNER JOIN Permissions
ON UserPermissions.PermissionID = Permissions.ID
WHERE Permissions.Name = 'TestRead'
UNION
SELECT Users.Name
FROM Users
INNER JOIN GroupMembers
ON GroupMembers.UserID = Users.ID
INNER JOIN Groups
ON GroupMembers.GroupID = Groups.ID
INNER JOIN GroupPermissions
ON GroupPermissions.GroupID = Groups.ID
INNER JOIN Permissions
ON GroupPermissions.PermissionID = Permissions.ID
WHERE Permissions.Name = 'TestRead';