<?php
if (!defined('ADMIN_PAGE_INCLUDED')) die('Access denied');


// Include and execute code for processing form submissions
include 'control-panel-actions.php';


// Database contents.
$allGroups = array();
$allUsers = array();
$allPermissions = array();

// TODO fetch all groups and users from database
/*
$allGroups = $db->selectAllGroups();
$allUsers = $db->selectAllUsers();
$allPermissions = $db->selectAllPermissions();
*/


// Determine the type of entity to display, if any
define ('TYPE_NONE', 0);
define ('TYPE_GROUP', 1);
define ('TYPE_USER', 2);

$type = TYPE_NONE;
$id = NULL;

// Determine if we should display either group or user controls
if (isset($_GET['g'])) {
  $type = TYPE_GROUP;
  $id = $_GET['g'];
} elseif (isset($_GET['u'])) {
  $type = TYPE_USER;
  $id = $_GET['u'];
}

// Fetch data from database that is relevant to the selected entity
switch ($type) {
case TYPE_GROUP:
  $name = (isset($allGroups[$id]) ? $allGroups[$id] : '');
  $permissions = array(); // TODO $db->selectPermissionsForGroup($id);
  $members = array(); // TODO $db->selectUsersForGroup($id);
  break;
  
case TYPE_USER:
  $name = (isset($allUsers[$id]) ? $allUsers[$id] : '');
  $permissions = array(); // TODO $db->selectPermissionsForUser($id);
  $groups = array(); // TODO $db->selectGroupsForUser($id);
  $inheritedPermissions = array();
  foreach ($groups as $group) {
    $inheritedPermissions[$group] = array(); // TODO $db->selectPermissionsForGroup($group);
  }
  break;
}


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Admin</title>
  </head>
  <body>
  
    <!-- Sidebar section containing navigation tree -->
    <section class="sidebar master-view">
      <div class="section-padding">
        <nav class="section-nav tree">
          <ul>
            
            <!-- Entire list of permission groups in database -->
            <li>Groups
              <ul>
<?php foreach ($allGroups as $groupId=>$groupName) : ?>
                <li><a href="?g=<?php echo $groupId ?>"><?php echo $groupName ?></a></li>
<?php endforeach; ?>
              </ul>
            </li>
            
            <!-- Entire list of users in database. (May be very long!) -->
            <li>Users
              <ul>
<?php foreach ($allUsers as $userId=>$userName) : ?>
                <li><a href="?u=<?php echo $userId ?>"><?php echo $userName ?></a></li>
<?php endforeach; ?>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </section>
    
    <!-- Main section containing administrative controls -->
    <section class="detail-view<?php
    switch ($type) {
      case TYPE_GROUP: echo ' group-details'; break;
      case TYPE_USER:  echo ' user-details';  break;
    }
    ?>">
      <div class="section-padding">
      
<?php if ($type === TYPE_GROUP) : ?>
        <!-- Group settings and details -->
        <h2>Group Settings</h2>
        
        <section class="details">
          <form method="post" action="">
            
            <!-- Database ID for the group -->
            <label for="group-id">ID</label>
            <input type="text" name="group-id" value="<?php echo $id ?>" disabled />
            
            <!-- Group name -->
            <label for="group-name">Name</label>
            <input type="text" name="group-name" value="<?php echo $name ?>" />
            
            <!-- Submission button -->
            <input type="hidden" name="type" value="details" />
            <input type="submit" value="Update" />
          </form>
        </section>
        
        <!-- Section to remove existing permissions from group -->
        <section class="remove-permissions">
          <h3>Permissions</h3>
          <form method="post" action="">
            
            <ul class="items">
<?php if (count($permissions) > 0) : ?>
              
              <!-- Existing permissions that can be removed -->
<?php foreach ($permissions as $permissionId=>$permissionName) : ?>
              <li>
                <label>
                  <input type="checkbox" name="remove-permission" value="<?php echo $permissionId ?>" />
                  <span class="checkbox-label"><?php echo $permissionName ?></span>
                </label>
              </li>
<?php endforeach; ?>
<?php else : ?>
              <li>This group has no permissions</li>
<?php endif; ?>
            </ul>
            
            <input type="hidden" name="type" value="remove-permissions" />
            <input type="submit" value="Remove Selected" />
          </form>
        </section>
        
        
        <!-- Section to add new permissions to the group -->
        <section class="add-permissions">
          <h3>Add Permissions</h3>
          <form method="post" action="">
            <ul class="items">
              <li>
                <select name="permission-select-1">
                  <option value="" selected></option>
<?php foreach ($allPermissions as $permissionId=>$permissionName) : ?>
                  <option value="<?php echo $permissionId ?>"><?php echo $permissionName ?></option>
<?php endforeach; ?>
                </select>
                or
                <input type="text" name="permission-text-1" />
              </li>
            </ul>
            
            <input type="hidden" name="type" value="add-permissions" />
            <input type="submit" value="Add Permissions" />
          </form>
        </section>
        
        
        <!-- Section to remove members from the group -->
        <section class="remove-members">
          <h3>Remove Members</h3>
          <form method="post" action="">
            
            <ul class="items">
<?php if (count($members) > 0) : ?>
              
              <!-- Existing members that can be removed -->
<?php foreach ($members as $memberId=>$memberName) : ?>
              <li>
                <label>
                  <input type="checkbox" name="remove-member" value="<?php echo $memberId ?>" />
                  <span class="checkbox-label"><?php echo $memberName ?></span>
                </label>
              </li>
<?php endforeach; ?>
<?php else : ?>
              <li>This group has no members</li>
<?php endif; ?>
            </ul>
            
            <input type="hidden" name="type" value="remove-members" />
            <input type="submit" value="Remove Selected" />
          </form>
        </section>
        
        
        <!-- Section to add new members to the group -->
        <section class="add-members">
          <h3>Add Members</h3>
          <form method="post" action="">
            <ul class="items">
              <li>
                <select name="member-select-1">
                  <option value="" selected></option>
<?php foreach ($allUsers as $memberId=>$memberName) : ?>
                  <option value="<?php echo $memberId ?>"><?php echo $memberName ?></option>
<?php endforeach; ?>
                </select>
                or
                <input type="text" name="member-text-1" />
              </li>
            </ul>
            
            <input type="hidden" name="type" value="add-members" />
            <input type="submit" value="Add Members" />
          </form>
        </section>


<?php elseif ($type === TYPE_USER) : ?>
        <!-- User settings and details -->
        <h2>User Settings</h2>
<?php endif; ?>
        
      </div>
    </section>
    
  </body>
</html>
