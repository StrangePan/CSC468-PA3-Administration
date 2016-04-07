<?php
require_once('user.php');

class MCSUser extends User
{
    private $username;
    private $displayName;

    //Constructor that takes necessary user data.
    public function __construct($user, $display)
    {
        parent::__construct();
        $this->username = $user;
        $this->displayName = $display;
    }

    //Returns the display name for the current user. Return is always a string
    public function getDisplayName()
    {
        return $this->displayName;
    }

    //Returns the username for the current user. Return is always a string
    public function getUsername()
    {
        return $this->username;
    }
	
	public function hasPermission($permission)
	{
		// To be implemented
		return true;
	}
	
	public function logOut()
	{
		// To be implemented
		parent::logOut();
	}
	
	
	public static function authenticate($username, $password)
	{
		return new self($username, 'TEST USER');
	}
	
	public static function declarePermission($permission)
	{
		// To be implemented
	}
}
