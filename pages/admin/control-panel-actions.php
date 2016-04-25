<?php
if (!defined('ADMIN_PAGE_INCLUDED')) die('Access denied');


// Only handle requests if they have been posted
if ($_SERVER['REQUEST_METHOD'] === 'POST'):


// Get form data that defines how to procede
$id = isset($_POST['id']) ? $_POST['id'] : FALSE;
$action = isset($_POST['action']) ? $_POST['action'] : FALSE;
$class = isset($_POST['class']) ? $_POST['class'] : FALSE;

// validate class
if ($class !== 'group' && $class !== 'user') {
  $class = FALSE;
}


if ($id !== FALSE && $action !== FALSE && $class !== FALSE) :


// remove permissions from group or user
if ($action === 'remove-permissions') {
  $permissions = isset($_POST['permission']) ? $_POST['permission'] : array();
  
  switch ($class)
  {
  case 'group':
    // TODO remove permissions from group
    break;
    
  case 'user':
    // TODO remove permissions from user
    break;
  }
}

// add permissions to group or user
elseif ($action === 'add-permissions') {
  
  if (isset($_POST['permission-id']) && isset($_POST['permission-name'])
      && count($_POST['permission-id']) == count($_POST['permission-name'])) {
    $count = count($_POST['permission-id']);
  } else {
    $count = 0;
  }
  
  $permissions = array(
    'ids'=>array(),
    'names'=>array()
  );
  
  $a = array(
    $count > 0 ? $_POST['permission-id'] : array(),
    $count > 0 ? $_POST['permission-name'] : array()
  );
  
  // Retrieve permission strings and IDs from form submission
  for ($i = 0; $i < $count; $i++) {
    if (trim($a[0][$i]) !== '') {
      $permissions['ids'][] = trim($a[0][$i]);
    }
    if (trim($a[1][$i]) !== '') {
      $permissions['names'][] = trim($a[1][$i]);
    }
  }
  
  switch ($class)
  {
  case 'group':
    // TODO remove permissions by ID from group/user
    break;
    
  case 'user':
    // TODO remove permissions by Name from group/user
    break;
  }
}

// remove members from group
elseif ($action === 'remove-members' && $class === 'group') {
  $members = isset($_POST['member']) ? $_POST['member'] : array();
  
  // TODO remove permissions from group
}

// added members to group
elseif ($action === 'add-members' && $class === 'group') {
  $members = isset($_POST['member']) ? $_POST['member'] : array();
  
  // TODO add new members to group
}

// remove member from group
elseif ($action === 'remove-from-groups' && $class === 'user') {
  $groups = isset($_POST['group']) ? $_POST['group'] : array();
  
  // TODO remove user from selected groups
}

// add member to group
elseif ($action === 'add-to-groups' && $class === 'user') {
  $groups = isset($_POST['group']) ? $_POST['group'] : array();
  
  // TODO add user to selected groups
}


endif; // $id, $action, and $class are set

endif; // $_SERVER['REQUEST_METHOD'] === 'POST';
