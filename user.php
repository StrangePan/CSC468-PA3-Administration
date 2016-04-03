interface User
{
    public static function getCurrentUser();
    public static function isAuthenticated();
    public function getDisplayName();
    public function getUsername();
}
