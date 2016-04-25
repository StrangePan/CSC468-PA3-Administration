<?php
if (!defined('ADMIN_PAGE_INCLUDED')) die('Access denied');

define ('TASK_UPDATE_DETAILS', 'updateDetails');
define ('TASK_ADD_PERMISSIONS', 'addPermissions');
define ('TASK_REMOVE_PERMISSIONS', 'removePermissions');
define ('TASK_ADD_MEMBERS', 'addMembers');
define ('TASK_REMOVE_MEMBERS', 'removeMembers');
define ('TASK_ADD_TO_GROUPS', 'addGroups');
define ('TASK_REMOVE_FROM_GROUPS', 'removeGroups');
define ('CLASS_GROUP', 'group');
define ('CLASS_USER', 'user');


// Include and execute code for processing form submissions
include 'control-panel-actions.php';


// Database contents.
$allGroups = array();
$allUsers = array();
$allPermissions = array();

// TODO fetch all groups and users from database
$allGroups = $db->fetchAllGroups();
$allUsers = $db->fetchAllUsers();
$allPermissions = $db->fetchAllPermissions();


// Determine the type of entity to display, if any
define ('TYPE_NONE', 0);
define ('TYPE_GROUP', 1);
define ('TYPE_USER', 2);

$type = TYPE_NONE;
$id = NULL;

// Determine if we should display either group or user controls
if (isset($_GET['g']) && isset($allGroups[$_GET['g']])) {
  $type = TYPE_GROUP;
  $id = $_GET['g'];
} elseif (isset($_GET['u']) && isset($allUsers[$_GET['u']])) {
  $type = TYPE_USER;
  $id = $_GET['u'];
}

// Fetch data from database that is relevant to the selected entity
switch ($type) {
case TYPE_GROUP:
  $name = (isset($allGroups[$id]) ? $allGroups[$id]['Name'] : '');
  $permissions = $db->fetchAllPermissionsInGroup($id);
  var_dump($permissions);
  $members = $db->fetchAllUsersInGroup($id);
  break;
  
case TYPE_USER:
  $name = (isset($allUsers[$id]) ? $allUsers[$id]['Name'] : '');
  $displayName = '';
  $permissions = $db->selectUserPermissionsById($id);
  $groups = $db->selectGroupsForUser($id);
/*
  $inheritedPermissions = array();
  foreach ($groups as $groupId=>$group) {
    $inheritedPermissions[$group] = array();$db->fetchAllPermissionsInGroup($groupId);
  }
*/
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
<?php foreach ($allGroups as $groupId=>$groupArray) : ?>
                <li><a href="?g=<?php echo $groupId ?>"><?php echo $groupArray['Name'] ?></a></li>
<?php endforeach; ?>
              </ul>
            </li>
            
            <!-- Entire list of users in database. (May be very long!) -->
            <li>Users
              <ul>
<?php foreach ($allUsers as $userId=>$userArray) : ?>
                <li><a href="?u=<?php echo $userId ?>"><?php echo $userArray['Name'] ?></a></li>
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
      case TYPE_NONE:  echo ' none-details';  break;
    }
    ?>">
      <div class="section-padding">
      
<?php if ($type === TYPE_GROUP) : ?>
        <!-- Group settings and details -->
        <h2>Group Settings</h2>
        
        <section class="details">
          <form method="post" action="">
            
            <!-- Database ID for the group -->
            <label for="id">ID</label>
            <input type="text" name="id" value="<?php echo $id ?>" disabled />
            
            <!-- Group name -->
            <label for="name">Name</label>
            <input type="text" name="name" value="<?php echo $name ?>" />
            
            <!-- Submission button -->
            <input type="hidden" name="task" value="<?php echo TASK_UPDATE_DETAILS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_GROUP ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Update" />
          </form>
        </section>


        <!-- Section to remove members from the group -->
        <section class="group-members">
          <h3>Members</h3>
          <form method="post" action="">
            
            <ul class="items">
<?php if (count($members) > 0) : ?>
              
              <!-- Existing members that can be removed -->
<?php foreach ($members as $memberId=>$memberArray) : ?>
              <li>
                <input type="checkbox" name="member[]" value="<?php echo $memberId ?>" />
                <a class="checkbox-label" href="?u=<?php echo $memberId ?>"><?php echo $memberArray['Name'] ?></a>
              </li>
<?php endforeach; ?>
<?php else : ?>
              <li>This group has no members</li>
<?php endif; ?>
            </ul>
            
            <input type="hidden" name="task" value="<?php echo TASK_REMOVE_MEMBERS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_GROUP ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Remove Selected" />
          </form>
        </section>
        
        
        <!-- Section to add new members to the group -->
        <section class="add-members">
          <h3>Add Members</h3>
          <form method="post" action="">
            <ul class="items">
              <li>
                <select name="member[]">
                  <option value="" selected></option>
<?php foreach ($allUsers as $memberId=>$memberArray) : ?>
                  <option value="<?php echo $memberId ?>"><?php echo $memberArray['Name'] ?></option>
<?php endforeach; ?>
                </select>
              </li>
            </ul>
            
            <input type="hidden" name="task" value="<?php echo TASK_ADD_MEMBERS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_GROUP ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Add Members" />
          </form>
        </section>


       <!-- Section to remove existing permissions from group -->
        <section class="remove-permissions">
          <h3>Permissions</h3>
          <form method="post" action="">
            
            <ul class="items">
<?php if (count($permissions) > 0) : ?>
              
              <!-- Existing permissions that can be removed -->
<?php foreach ($permissions as $permissionId=>$permissionArray) : ?>
              <li>
                <label>
                  <input type="checkbox" name="permission[]" value="<?php echo $permissionId ?>" />
                  <span class="checkbox-label"><?php echo $permissionArray['Name'] ?></span>
                </label>
              </li>
<?php endforeach; ?>
<?php else : ?>
              <li>This group has no permissions</li>
<?php endif; ?>
            </ul>
            
            <input type="hidden" name="task" value="<?php echo TASK_REMOVE_PERMISSIONS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_GROUP ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Remove Selected" />
          </form>
        </section>
        
        
        <!-- Section to add new permissions to the group -->
        <section class="add-permissions">
          <h3>Add Permissions</h3>
          <form method="post" action="">
            <ul class="items">
              <li>
                <select name="permission-id[]">
                  <option value="" selected></option>
<?php foreach ($allPermissions as $permissionId=>$permissionArray) : ?>
                  <option value="<?php echo $permissionId ?>"><?php echo $permissionArray['Name'] ?></option>
<?php endforeach; ?>
                </select>
                or
                <input type="text" name="permission-name[]" />
              </li>
            </ul>
            
            <input type="hidden" name="task" value="<?php echo TASK_ADD_PERMISSIONS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_GROUP ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Add Permissions" />
          </form>
        </section>
        
        
<?php elseif ($type === TYPE_USER) : ?>
        <!-- User settings and details -->
        <h2>User Settings</h2>
        
        <section class="details">
          <form method="post" action="">
            
            <!-- Database ID for the user -->
            <label for="id">ID</label>
            <input type="text" name="id" value="<?php echo $id ?>" disabled />
            
            <!-- Username -->
            <label for="username">Username</label>
            <input type="text" name="username" value="<?php echo $name ?>" disabled />
            
            <!-- User display name -->
            <label for="name">Display Name</label>
            <input type="text" name="name" value="<?php echo $displayName ?>" disabled />
            
            <!-- Submission button -->
            <input type="hidden" name="task" value="<?php echo TASK_UPDATE_DETAILS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_USER ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
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
<?php foreach ($permissions as $permissionId=>$permissionArray) : ?>
              <li>
                <label>
                  <input type="checkbox" name="permission[]" value="<?php echo $permissionId ?>" />
                  <span class="checkbox-label"><?php echo $permissionArray['Name'] ?></span>
                </label>
              </li>
<?php endforeach; ?>
<?php else : ?>
              <li>This user has no permissions</li>
<?php endif; ?>
            </ul>
            
            <input type="hidden" name="task" value="<?php echo TASK_REMOVE_PERMISSIONS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_USER ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Remove Selected" />
          </form>
        </section>
        
        
        <!-- Section to add new permissions to the group -->
        <section class="add-permissions">
          <h3>Add Permissions</h3>
          <form method="post" action="">
            <ul class="items">
              <li>
                <select name="permission-id[]">
                  <option value="" selected></option>
<?php foreach ($allPermissions as $permissionId=>$permissionArray) : ?>
                  <option value="<?php echo $permissionId ?>"><?php echo $permissionArray['Name'] ?></option>
<?php endforeach; ?>
                </select>
                or
                <input type="text" name="permission-name[]" />
              </li>
            </ul>
            
            <input type="hidden" name="task" value="<?php echo TASK_ADD_PERMISSIONS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_USER ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Add Permissions" />
          </form>
        </section>
        
        
        <!-- Section to remove the user from groups  -->
        <section class="remove-from-groups">
          <h3>Groups</h3>
          <form method="post" action="">
            
            <ul class="items">
<?php if (count($groups) > 0) : ?>
              
              <!-- Existing members that can be removed -->
<?php foreach ($groups as $groupId=>$groupArray) : ?>
              <li>
                <input type="checkbox" name="group[]" value="<?php echo $groupId ?>" />
                <a class="checkbox-label" href="?g=<?php echo $groupId ?>"><?php echo $groupArray['Name'] ?></a>
              </li>
<?php endforeach; ?>
<?php else : ?>
              <li>This user belongs to no groups</li>
<?php endif; ?>
            </ul>
            
            <input type="hidden" name="task" value="<?php echo TASK_REMOVE_FROM_GROUPS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_USER ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Remove from Selected" />
          </form>
        </section>
        
        
        <!-- Section to add user to new groups -->
        <section class="add-to-groups">
          <h3>Add to Groups</h3>
          <form method="post" action="">
            <ul class="items">
              <li>
                <select name="group[]">
                  <option value="" selected></option>
<?php foreach ($allGroups as $groupId=>$groupArray) : ?>
                  <option value="<?php echo $groupId ?>"><?php echo $groupArray['Name'] ?></option>
<?php endforeach; ?>
                </select>
              </li>
            </ul>
            
            <input type="hidden" name="task" value="<?php echo TASK_ADD_TO_GROUPS ?>" />
            <input type="hidden" name="class" value="<?php echo CLASS_USER ?>" />
            <input type="hidden" name="id" value="<?php echo $id ?>" />
            <input type="submit" value="Add to Groups" />
          </form>
        </section>


<?php elseif ($type === TYPE_NONE) : ?>
        
        <h2>Select a group or user from the list</h2>
        
<?php endif; ?>
        
      </div>
    </section>
    
  </body>
</html>
