<?php

class MCSDatabase extends SQLite3
{
  const DATABASE_FILENAME = 'MCS.db';
  const SCRIPTS_DIRECTORY = 'sql';
  const SCRIPT_CREATE_TABLES = 'CreateTables.sql';
  
  function __construct()
  {
    $this->open(__DIR__.'/'.self::DATABASE_FILENAME);
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
    $query = file_get_contents(__DIR__.'/'.self::SCRIPTS_DIRECTORY.'/'.self::SCRIPT_CREATE_TABLES);
    if ($query)
    {
      $this->query($query);
    }
  }
  
  /**
   * Get all permissions that a user has, factoring in group inheritance
   */
  function selectPermissionsForUserByName($username)
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
  
  function selectPermissionsForUserByID($ID)
  {
    $ID = parent::escapeString($ID);
    
    $query = <<<EOF
      SELECT Permissions.Name as Permission
      FROM Permissions
      INNER JOIN UserPermissions
      ON UserPermissions.PermissionID = Permissions.ID
      INNER JOIN Users
      ON UserPermissions.UserID = Users.ID
      WHERE Users.ID = '$ID'
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
      WHERE Users.ID = '$ID';
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
      SELECT Users.ID, Users.Name
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
      while ($row = $results->fetchArray())
      {
        $users[] = $row[0];
        $users[$row[0]] = $row[1];
      }
    }
    
    return $users;
  }
  
  function selectGroupsForUser($userid)
  {
    $userid = parent::escapeString($userid);
    
    $query = <<<EOF
      SELECT Groups.*
	  FROM Groups
	  INNER JOIN GroupMembers
	  ON Groups.ID = GroupMembers.GroupID
	  INNER JOIN Users
	  ON GroupMembers.UserID = Users.ID
	  WHERE Users.ID = $userid;
EOF;
    
    $results = $this->query($query);
    $groups = array();
    
    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $groups[$row['ID']] = $row;
      }
    }
    
    return $groups;
  }

  function selectUserPermissionsByName($username)
  {
    $username = parent::escapeString($username);
    
    $query = <<<EOF
      SELECT Permissions.*
	  FROM Permissions
	  INNER JOIN UserPermissions
	  ON Permissions.ID = UserPermissions.PermissionID
	  INNER JOIN Users
	  ON UserPermissions.UserID = Users.ID
	  WHERE Users.Name = '$username';
EOF;
    
    $results = $this->query($query);
    $userPermissions = array();
    
    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $userPermissions[$row['ID']] = $row;
      }
    }
    
    return $userPermissions;
  }

  function selectUserPermissionsById($username)
  {
    $username = parent::escapeString($username);
    
    $query = <<<EOF
      SELECT Permissions.*
	  FROM Permissions
	  INNER JOIN UserPermissions
	  ON Permissions.ID = UserPermissions.PermissionID
	  INNER JOIN Users
	  ON UserPermissions.UserID = Users.ID
	  WHERE Users.ID = $username;
EOF;

    $results = $this->query($query);
    $userPermissions = array();
    
    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $userPermissions[$row['ID']] = $row;
      }
    }
    
    return $userPermissions;
  }

  /**
   * Attempts to insert a user with a given name, returns either an array
   * representing the record, or an empty array.
   */
  function createUser($username)
  {
    $username = parent::escapeString($username);

    $query = <<<EOF
      INSERT INTO Users(Name)
      Values('$username');
EOF;

    $results = $this->query($query);

    if($results == true)
    {
      $query = <<<EOF
        SELECT *
        FROM Users
        WHERE Users.Name = '$username';
EOF;

      $results = $this->query($query);
      $users = array();
      
      if (!is_bool($results))
      {
        while ($row = $results->fetchArray())
        {
          $users[$row['ID']] = $row;
        }
      }
    }
    
    return $users;
  }
  
  /**
   * Attempts to remove a user, returns a boolean
   * representing success.
   */
  function removeUser($user)
  {
    $user = parent::escapeString($user);

    $query = <<<EOF
      DELETE FROM Users
      Where Users.Name = $user
EOF;

    $results = $this->query($query);
    
    return $results;
  }

  /**
   * Attempts to insert a group with a given name, returns either an array
   * representing the record, or an empty array.
   */
  function createGroup($group)
  {
    $group = parent::escapeString($group);

    $query = <<<EOF
      INSERT INTO Groups(Name)
      Values('$group');
EOF;

    $results = $this->query($query);

    if($results == true)
    {
      $query = <<<EOF
        SELECT *
        FROM Groups
        WHERE Groups.Name = '$group';
EOF;

      $results = $this->query($query);
      $groups = array();
      
      if (!is_bool($results))
      {
        while ($row = $results->fetchArray())
        {
          $groups[$row['ID']] = $row;
        }
      }
    }
    
    return $groups;
  }
  
  /**
   * Attempts to remove a group, returns a boolean
   * representing success.
   */
  function removeGroup($group)
  {
    $group = parent::escapeString($group);

    $query = <<<EOF
      DELETE FROM Groups
      Where Groups.Name = $group
EOF;

    $results = $this->query($query);
    
    return $results;
  }

  /**
   * Attempts to insert a permission with a given name, returns either an array
   * representing the record, or an empty array.
   */
  function createPermission($permission)
  {
    $permission = parent::escapeString($permission);

    $query = <<<EOF
      INSERT OR IGNORE INTO Permissions(Name)
      Values('$permission');
EOF;

    $results = $this->query($query);

    if($results == true)
    {
      $query = <<<EOF
        SELECT *
        FROM Permissions
        WHERE Name = '$permission';
EOF;

      $results = $this->query($query);
      
      if (!is_bool($results))
      {
        while ($row = $results->fetchArray())
        {
          return $row['ID'];
        }
      }
    }
    
    return FALSE;
  }
  
  /**
   * Attempts to remove a permission, returns a boolean
   * representing success.
   */
  function removePermission($permission)
  {
    $permission = parent::escapeString($permission);

    $query = <<<EOF
      DELETE FROM Permissions
      Where Permissions.Name = $permission
EOF;

    $results = $this->query($query);
    
    return $results;
  }

  /**
   * Attempts to insert a user into a group, returns a boolean
   * representing success.
   */
  function addGroupMember($group, $user)
  {
    $group = parent::escapeString($group);
    $user = parent::escapeString($user);

    $query = <<<EOF
      INSERT INTO GroupMembers(GroupID, UserID)
      Values($group, $user);
EOF;

    $results = $this->query($query);
    
    return $results;
  }

  /**
   * Attempts to remove a user from a group, returns a boolean
   * representing success.
   */
  function removeGroupMember($group, $user)
  {
    $group = parent::escapeString($group);
    $username = parent::escapeString($user);

    $query = <<<EOF
      DELETE FROM GroupMembers
      Where UserID = $user
      AND GroupID = $group;
EOF;

    $results = $this->exec($query);
    
    return $results;
  }

  /**
   * Attempts to add a permission to a user, returns a boolean
   * representing success.
   */
  function addPermissionToUser($permission, $user)
  {
    $permission = parent::escapeString($permission);
    $username = parent::escapeString($user);

    $query = <<<EOF
      INSERT OR IGNORE INTO UserPermissions(PermissionID, UserID)
      VALUES( $permission, $user );
EOF;

    $results = $this->exec($query);
    
    return $results;
  }
  
  /**
   * Attempts to remove a permission from a user, returns a boolean
   * representing success.
   */
  function removePermissionFromUser($permission, $user)
  {
    $permission = parent::escapeString($permission);
    $username = parent::escapeString($user);

    $query = <<<EOF
      DELETE FROM UserPermissions
      Where UserID = $user AND PermissionID = $permission;
EOF;

    $results = $this->exec($query);
    
    return $results;
  }

  /**
   * Attempts to add a permission to a group, returns a boolean
   * representing success.
   */
  function addPermissionToGroup($permission, $group)
  {
    $permission = parent::escapeString($permission);
    $group = parent::escapeString($group);

    $query = <<<EOF
      INSERT OR IGNORE INTO GroupPermissions(PermissionID, GroupID)
      VALUES( $permission, $group );
EOF;

    $results = $this->exec($query);
    
    return $results;
  }
  
  /**
   * Attempts to remove a permission from a group, returns a boolean
   * representing success.
   */
  function removePermissionFromGroup($permission, $group)
  {
    $permission = parent::escapeString($permission);
    $group = parent::escapeString($group);

    $query = <<<EOF
      DELETE FROM GroupPermissions
      Where GroupID = $group AND PermissionID = $permission;
EOF;

    $results = $this->exec($query);
    
    return $results;
  }
  
  function selectInheritedPermissions($username)
  {
    $username = parent::escapeString($username);
    
    $query = <<<EOF
      SELECT Groups.Name, Permissions.Name
      FROM Groups
      INNER JOIN GroupPermissions
	  ON Groups.ID = GroupPermissions.GroupID
	  INNER JOIN Permissions
	  ON GroupPermissions.PermissionID = Permissions.ID
	  INNER JOIN GroupMembers
	  ON GroupMembers.GroupID = Groups.ID
	  INNER JOIN Users
	  ON GroupMembers.UserID = Users.ID
	  WHERE Users.Name = '$username';
EOF;
    
    $results = $this->query($query);
    $groupPermissions = array();
    
    if (!is_bool($results))
    {	  
      while ($row = $results->fetchArray())
      {
		if(!array_key_exists($row[0], $groupPermissions))
		{
			$groupPermissions[] = $row[0];
		}
        $groupPermissions[$row[0]] = $row[1];
      }
    }
    
    return $groupPermissions;
  }

  /**
   * Retrieves all users in the database. Returns an array indexed by
   * ID.
   */
  function fetchAllUsers()
  {
    $query = <<<EOF
      SELECT * FROM Users;
EOF;

    $results = $this->query($query);
    $users = array();

    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $users[$row['ID']] = $row;
      }
    }
    
    return $users;
  }

  /**
   * Retrieves all groups in the database. Returns an array indexed by
   * ID.
   */
  function fetchAllGroups()
  {
    $query = <<<EOF
      SELECT * FROM Groups;
EOF;

    $results = $this->query($query);
    $groups = array();

    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $groups[$row['ID']] = $row;
      }
    }
    
    return $groups;
  }

  /**
   * Retrieves all permissions in the database. Returns an array indexed by
   * ID.
   */
  function fetchAllPermissions()
  {
    $query = <<<EOF
      SELECT * FROM Permissions;
EOF;

    $results = $this->query($query);
    $permissions = array();

    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $permissions[$row['ID']] = $row;
      }
    }
    
    return $permissions;
  }

  /**
   * Retrieves all users in a group. Returns an array indexed by
   * ID.
   */
  function fetchAllUsersInGroup($group)
  {
    $group = parent::escapeString($group);
    
    $query = <<<EOF
      SELECT Users.*
	  FROM Users
	  INNER JOIN GroupMembers
	  ON Users.ID = GroupMembers.UserID
	  INNER JOIN Groups
	  ON Groups.ID = GroupMembers.GroupID
	  WHERE
	  Groups.ID = $group;
EOF;

    $results = $this->query($query);
    $users = array();

    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $users[$row['ID']] = $row;
      }
    }
    
    return $users;
  }
  
  function fetchAllPermissionsInGroup($group)
  {
    $group = parent::escapeString($group);
    
    $query = <<<EOF
      SELECT Permissions.*
      FROM Permissions
      INNER JOIN GroupPermissions
      ON GroupPermissions.PermissionID = Permissions.ID
      INNER JOIN Groups
      ON Groups.ID = GroupPermissions.GroupID
      WHERE Groups.ID = $group;
EOF;

    $results = $this->query($query);
    $users = array();

    if (!is_bool($results))
    {
      while ($row = $results->fetchArray())
      {
        $users[$row['ID']] = $row;
      }
    }
    
    return $users;
  }
    

  /**
   * Attempts to update a group with a new name, returns either an array
   * representing the record, or an empty array.
   */
  function updateGroupName($groupid, $newGroup)
  {
    $groupid = parent::escapeString($groupid);
    $newGroup = parent::escapeString($newGroup);

    $query = <<<EOF
      UPDATE Groups
      SET Name = '$newGroup'
      WHERE Groups.ID = $groupid;
EOF;

    $this->exec($query);

    if($results == true)
    {
      $query = <<<EOF
        SELECT *
        FROM Groups
        WHERE Groups.Name = '$newGroup';
EOF;

      $results = $this->query($query);
      $groups = array();
      
      if (!is_bool($results))
      {
        while ($row = $results->fetchArray())
        {
          $groups[$row['ID']] = $row;
        }
      }
    }
    
    return $groups;
  }
  
  function declarePermission($permission)
  {
	if(empty($permission))
		return false;
	  
	$permission = parent::trim($permission);
    $permission = parent::escapeString($permission);
	
    $query = <<<EOF
	INSERT OR IGNORE INTO Permissions(Name) 
	VALUES('$permission');

	INSERT OR IGNORE INTO Files(Name)
	VALUES('$filePath');

	INSERT OR IGNORE INTO FilePermissions(PermissionID, FileID)
	VALUES( (SELECT ID FROM Permissions WHERE Permissions.ID = '$permission'), (SELECT ID FROM Files WHERE Files.Name = '$filePath') )
EOF;

    $results = $this->query($query);
  }
}

