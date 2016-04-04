/**
 * Interface for interacting with a website user.
 */
abstract class User
{
    private static $instance = NULL;

    /**
     * Class constructor. Stores this instance of the class as the main
     * instance.
     */
    public function __construct()
    {
        if (self::$instance == null)
        {
            self::$instance = $this;
        }
    }
    
    /**
     * This method will return null if no user has been created,
     * and will return the current user object otherwise.
     */
    public static function getCurrentUser()
    {
        return self::$instance;
    }
    
    /**
     * Always returns a boolean value representing whether a user has
     * beene created or not.
     */
    public static function isAuthenticated()
    {
        return (self::$instance != NULL);
    }
    
    /**
     * Returns the display name for the current user. Return type is always a
     * string.
     */
    abstract public function getDisplayName();

    /**
     * Gets the username for the current user. Return type is always a string.
     */
    abstract public function getUsername();
}
