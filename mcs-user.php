require_once('user.php');

class MCSUser extends User
{
    private $username;
    private $displayName;

    //Constructor that takes necessary user data.
    public __construct($user, $display)
    {
        parent::__construct();
        $this->username = $user;
        $this->displayName = $display;
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
