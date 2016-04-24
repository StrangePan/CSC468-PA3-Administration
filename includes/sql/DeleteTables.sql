-- Drop tables and indexes in the reverse order they were created in
-- CreateTables.sql

DROP TABLE GroupMembers;

DROP TABLE GroupPermissions;

DROP INDEX Groups_Name_ind;

DROP TABLE Groups;


DROP TABLE UserPermissions;

DROP INDEX Users_Name_ind;

DROP TABLE Users;



DROP TABLE FilePermissions;

DROP INDEX Permissions_Name_ind;

DROP TABLE Permissions;

DROP INDEX Files_Name_ind;

DROP TABLE Files;


