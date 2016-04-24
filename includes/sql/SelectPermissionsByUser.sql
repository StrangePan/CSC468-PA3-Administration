--Select all of the permissions that a user has
SELECT Permissions.Name as Permission
FROM Permissions
INNER JOIN UserPermissions
ON UserPermissions.PermissionID = Permissions.ID
INNER JOIN Users
ON UserPermissions.UserID = Users.ID
WHERE Users.Name = 'rschrader'
UNION
SELECT Permissions.Name as Permission
FROM Permissions
INNER JOIN GroupPermissions
ON GroupPermissions.PermissionID = Permissions.ID
INNER JOIN Groups
ON GroupPermissions.GroupID = Groups.ID
INNER JOIN GroupMembers
ON GroupMembers.GroupID = Groups.ID
INNER JOIN Users
ON Users.ID = GroupMembers.UserID
WHERE Users.Name = 'rschrader';
