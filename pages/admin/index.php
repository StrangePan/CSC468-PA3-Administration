<?php
define('ADMIN_PAGE_INCLUDED', TRUE);
 
 
/*
 * This section checks to make sure the user has adequate permissions. If they
 * do not, an 'access denied' page is served instead of the normal admin
 * interface.
 */

// Deny access to page if user is not logged in or if they don't have the admin
// permission.
if (!User::isAuthenticated() || !User::getCurrentUser()->hasPermission(ADMIN_PERMISSION)) :

// Include code for handling access denied errors
include 'access-denied.php';

else:


/*
 * If we reach this section, it means the user has sufficient priviledges to
 * view this page.
 */
 
// Include code for handling and processing requests and posts
include 'control-panel.php';

endif;
