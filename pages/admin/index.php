<?php
/*
 * This section checks to make sure the user has adequate permissions. If they
 * do not, an 'access denied' page is served instead of the normal admin
 * interface.
 */
 

// Deny access to page if user is not logged in or if they don't have the admin
// permission.
if (!User::isAuthenticated() || !User::getCurrentUser()->hasPermission(ADMIN_PERMISSION)) :
?>
<!DOCTYPE>
<html>
  <head>
    <title>Access Denied</title>
  </head>
  <body>
    <div class="error message">
      <h1 class="subject">Access Denied</h1>
      <p class="body">You must be logged in and have permission to access this
      page</p>
    </div>
  </body>
</html>
<?php
else:



/*
 * If we reach this section, it means the user has sufficient priviledges to
 * view this page.
 */

// Database contents.
$allGroups = array();
$allUsers = array();

// TODO fetch all groups and users from database
/*
$allGroups = $db->selectAllGroups();
$allUsers = $db->selectAllUsers();
*/

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Admin</title>
  </head>
  <body>
  
  </body>
</html>
<?php endif;
