<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
  if (isset($_POST['username']) && isset($_POST['password']))
  {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    User::authenticate($username, $password);
  }
  elseif (isset($_POST['logout']))
  {
    if (User::isAuthenticated())
    {
      User::getCurrentUser()->logOut();
    }
  }
}

