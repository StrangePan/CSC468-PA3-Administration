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
  
  foreach ($permissions as $permissionId) {
    switch ($class)
    {
    case CLASS_GROUP:
      $db->removePermissionFromGroup($permissionId, $id);
      break;
      
    case CLASS_USER:
      $db->removePermissionFromUser($permissionId, $id);
      break;
    }
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
    foreach ($permissions['ids'] as $permissionId) {
      $db->addPermissionToGroup($permissionId, $id);
    }
    foreach ($permissions['names'] as $permissionName) {
      $permissionId = $db->createPermission($permissionName);
      $db->addPermissionToGroup($permissionId, $id);
    }
    break;
    
  case CLASS_USER:
    foreach ($permissions['ids'] as $permissionId) {
      $db->addPermissionToUser($permissionId, $id);
    }
    foreach ($permissions['names'] as $permissionName) {
      $permissionId = $db->createPermission($permissionName);
      $db->addPermissionToUser($permissionId, $id);
    }
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
  
  foreach ($groups as $groupId) {
    $db->removeGroupMember($groupId, $id);
  }
}

// add member to group
elseif ($task === TASK_ADD_TO_GROUPS && $class === CLASS_USER) {
  $groups = isset($_POST['group']) ? $_POST['group'] : array();
  
  foreach ($groups as $groupId) {
    $db->addGroupMember($groupId, $id);
  }
}


endif; // $id, $task, and $class are set

endif; // $_SERVER['REQUEST_METHOD'] === 'POST';
