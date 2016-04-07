<?php
/**
 * Interface for interacting with a website user.
 */
abstract class User
{
	const SESSION_FIELD = 'current_user';
	
	private static $subclass = null;
	
	
    /**
     * Class constructor. Stores this instance of the class as the main
     * instance.
     */
    public function __construct()
    {
        // nothing else to do here
    }
	
	
	/**
	 * This method will attempt to authenticate the given username and password
	 * against Active Directory. If the supplied credentials are correct and the
	 * user is successfully logged in, then subsequent calls to
	 * `getCurrentUser()` will return the newly logged in user.
	 * 
	 * parameter string $username Username of user to log in.
	 * parameter string $password The password to authenticate with.
	 *
	 * return boolean `true` if the user logged in successfully, `false` if
	 *                not.
	 */
	public static function authenticate($username, $password)
	{
		// To be implemented
		if (self::$subclass != null)
		{
			$subclass = self::$subclass;
			$instance = $subclass::authenticate($username, $password);
			if ($instance != null)
			{
				$_SESSION[self::SESSION_FIELD] = $instance;
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
    
	
    /**
     * Always returns a boolean value representing whether a user has
     * been created or not.
	 *
	 * return boolean `true` if a user is logged in, `false` if not.
     */
    public static function isAuthenticated()
    {
        return (self::getCurrentUser() != null);
    }
    
	
    /**
     * This method will return null if no user has been created,
     * and will return the current user object otherwise.
	 *
	 * return User The currently logged in user.
	 * return null If no user is logged in, returns `null`.
     */
    public static function getCurrentUser()
    {
		// to be implemented
		if (isset($_SESSION[self::SESSION_FIELD])
			&& gettype($_SESSION[self::SESSION_FIELD]) == 'object'
			&& $_SESSION[self::SESSION_FIELD] instanceof User)
		{
			return $_SESSION[self::SESSION_FIELD];
		}
		else
		{
			return null;
		}
    }
	
	
	/**
	 * Declares a permission for use on the current page. Should be called at
	 * the top of the file before the requested permission is used.
	 * 
	 * parameter string $permission The permission string to declare
	 */
	public static function declarePermission($permission)
	{
		if (self::$subclass != null)
		{
			$subclass = self::$subclass;
			$subclass::declarePermission($permission);
		}
	}
	
	
    /**
     * Gets the username for the current user. Return type is always a string.
	 *
	 * return string The username string for the user. For students, this will
	 *               be a student ID, whereas faculty will have a faculty ID.
     */
    public abstract function getUsername();
	
	
    /**
     * Returns the display name for the current user. Return type is always a
     * string.
     */
    public abstract function getDisplayName();
	
	
	/**
	 * Checks if the current user has the requested permission. Factors in
	 * inheritance from group membership, i.e. if this user is a member of any
	 * permission group having this permission, then this user also has that
	 * permission.
	 *
	 * parameter string $permission The permission string to check for. This
	 *                              permission string should be declared before
	 *                              being checked using `declarePermission()`.
	 * 
	 * return boolean `true` if the user has the given permission, `false` if
	 *                if not.
	 */
	public abstract function hasPermission($permission);
	
	
	/**
	 * Logs the current user out. This method will always be successful, and
	 * thus does not return anything.
	 */
	public function logOut()
	{
		if (isset($_SESSION[self::SESSION_FIELD]) && $_SESSION[self::SESSION_FIELD] === $this)
		{
			unset($_SESSION[self::SESSEION_FEILD]);
		}
	}
	
	
	/**
	 * Tells this class what subclass to use when authenticating and calling
	 * certain initialization functions.
	 *
	 * parameter string $subclass The class to use for initialization and
	 *                            authentication.
	 */
	public static function setSubclass($subclass)
	{
		self::$subclass = $subclass;
	}
}
