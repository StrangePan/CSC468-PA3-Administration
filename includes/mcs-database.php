<?php

class MCSDatabase extends SQLite3
{
  const DATABASE_FILENAME = __DIR__.'/MCS.db';
  const SCRIPTS_DIRECTORY = __DIR__.'/sql';
  const SCRIPT_CREATE_TABLES = self::SCRIPTS_DIRECTORY.'/CreateTables.sql';
  
  function __construct()
  {
    $this->open(self::DATABASE_FILENAME);
  }
  
  function __destruct()
  {
    $this->close();
  }
  
  /**
   * Creates tables.
   */
  function createTables()
  {
    $query = file_get_contents(self::SCRIPT_CREATE_TABLES);
    if ($query)
    {
      $this->query($query);
    }
  }
  
  /**
   * Get all permissions that a user has, factoring in group inheritance
   */
  function selectPermissionsForUser($username)
  {
    $username = parent::escapeString($username);
    
    $query = <<<EOF
      SELECT Permissions.Name as Permission
      FROM Permissions
      INNER JOIN UserPermissions
      ON UserPermissions.PermissionID = Permissions.ID
      INNER JOIN Users
      ON UserPermissions.UserID = Users.ID
      WHERE Users.Name = '$username'
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
      WHERE Users.Name = '$username';
EOF;
    
    $results = $this->query($query);
    $permissions = array();
    
    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $permissions[] = $row[0];
        $permissions[$row[0]] = TRUE;
      }
    }
    
    return $permissions;
  }
  
  /**
   * Gets all the users that have a permission
   */
  function selectUsersForPermission($permission)
  {
    $permission = parent::escapeString($permission);
    
    $query = <<<EOF
      SELECT Users.Name as User
      FROM Users
      INNER JOIN UserPermissions
      ON UserPermissions.UserID = Users.ID
      INNER JOIN Permissions
      ON UserPermissions.PermissionID = Permissions.ID
      WHERE Permissions.Name = '$permission'
      UNION
      SELECT Users.Name as User
      FROM Users
      INNER JOIN GroupMembers
      ON GroupMembers.UserID = Users.ID
      INNER JOIN Groups
      ON GroupMembers.GroupID = Groups.ID
      INNER JOIN GroupPermissions
      ON GroupPermissions.GroupID = Groups.ID
      INNER JOIN Permissions
      ON GroupPermissions.PermissionID = Permissions.ID
      WHERE Permissions.Name = '$permission';
EOF;
    
    $results = $this->query($query);
    $users = array();
    
    if (!is_bool($results))
    {
      while ($ow = $results->fetchArray())
      {
        $users[] = $row[0];
        $users[$row[0]] = TRUE;
      }
    }
    
    return $users;
  }
  
}

