class MCSUser implements User
{
    private static $instance;
    private $username;
    private $displayName;

    //Constructor that takes necessary user data.
    public MCSUser($user, $display)
    {
        $this->vars[$username] = $user;
        $this->vars[$displayName] = $display;
        MCSUser::$instance = $this;
    }

    //This method will return null if no user has been created,
    //and will return the current user object otherwise.
    public static function getCurrentUser()
    {
        return MCSUser::$instance;
    }
    
    //Always returns a boolean value representing whether a user has
    //been created or not.
    public function isAuthenticated()
    {
        return (MCSUser::$instance == NULL);
    }

    //Returns the display name for the current user. Return is always a string
    public function getDisplayName()
    {
        return self::$displayName;
    }

    //Returns the username for the current user. Return is always a string
    public function getDisplayName()
    {
        return self::$username;
    }
}
