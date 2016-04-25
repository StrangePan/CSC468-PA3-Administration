<?php
if (!defined('ADMIN_PAGE_INCLUDED')) die('Access denied');


// Only handle requests if they have been posted
if ($_SERVER['REQUEST_METHOD'] === 'POST'):


// Get form data that defines how to procede
$id = isset($_POST['id']) ? $_POST['id'] : FALSE;
$task = isset($_POST['task']) ? $_POST['task'] : FALSE;
$class = isset($_POST['class']) ? $_POST['class'] : FALSE;

// validate class
if ($class !== CLASS_GROUP && $class !== CLASS_USER) {
  $class = FALSE;
}

//die($id.' '.$task.' '.$class);

if ($id !== FALSE && $task !== FALSE && $class !== FALSE) :


// update entity details
if ($task === TASK_UPDATE_DETAILS && $class === CLASS_GROUP) {
  $name = isset($_POST['name']) ? $_POST['name'] : FALSE;
  
  if ($name !== FALSE) {
    $db->updateGroupName($id, $name);
  }
}

// remove permissions from group or user
elseif ($task === TASK_REMOVE_PERMISSIONS) {
  $permissions = isset($_POST['permission']) ? $_POST['permission'] : array();
  
  switch ($class)
  {
  case CLASS_GROUP:
    // TODO remove permissions from group
    break;
    
  case CLASS_USER:
    // TODO remove permissions from user
    break;
  }
}

// add permissions to group or user
elseif ($task === TASK_ADD_PERMISSIONS) {
  
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
  case CLASS_GROUP:
    // TODO remove permissions by ID from group/user
    break;
    
  case CLASS_USER:
    // TODO remove permissions by Name from group/user
    break;
  }
}

// remove members from group
elseif ($task === TASK_REMOVE_MEMBERS && $class === CLASS_GROUP) {
  $members = isset($_POST['member']) ? $_POST['member'] : array();
  
  foreach ($members as $userId) {
    $db->removeGroupMember($id, $userId);
  }
}

// added members to group
elseif ($task === TASK_ADD_MEMBERS && $class === CLASS_GROUP) {
  $members = isset($_POST['member']) ? $_POST['member'] : array();
  
  foreach ($members as $userId) {
    $db->addGroupMember($id, $userId);
  }
}

// remove member from group
elseif ($task === TASK_REMOVE_FROM_GROUPS && $class === CLASS_USER) {
  $groups = isset($_POST['group']) ? $_POST['group'] : array();
  
  // TODO remove user from selected groups
}

// add member to group
elseif ($task === TASK_ADD_TO_GROUPS && $class === CLASS_USER) {
  $groups = isset($_POST['group']) ? $_POST['group'] : array();
  
  // TODO add user to selected groups
}


endif; // $id, $task, and $class are set

endif; // $_SERVER['REQUEST_METHOD'] === 'POST';
