<?php
require_once('user.php');

// Attempt to create configuration file before requiring it
if (!file_exists('mcs-user-config.php') && file_exists('mcs-user-config-default.php')) {
    copy('mcs-user-config-default.php', 'mcs-user-config.php');
}
require_once('mcs-user-config.php');

class MCSUser extends User
{
    private $username;
    private $displayName;

    //Constructor that takes necessary user data.
    public function __construct($username, $displayName)
    {
        parent::__construct();
        $this->username = $username;
        $this->displayName = $displayName;
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
        global $MCSUSER_CONFIG;
        
        $LDAPDOMAIN = $MCSUSER_CONFIG['ldap_domain'];
        $LDAPHOSTNAME = $MCSUSER_CONFIG['ldap_hostname'];
        $LDAPPORT = $MCSUSER_CONFIG['ldap_port'];
        $LDAPPROTOCOLVERSION = 3;
        $LDAPREFERRALS = 0;
        
        // Attempt to establish connection with LDAP server
        $ldapConnection = ldap_connect($LDAPHOSTNAME);
        if ($ldapConnection === FALSE)
        {
            // Unable to connect, return false
            return null;
        }
        
        // Configure LDAP connection
        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, $LDAPPROTOCOLVERSION);
        ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, $LDAPREFERRALS);
        
        // Construct LDAP login and bind (authenticate)
        $ldapUsername = $username . $LDAPDOMAIN;
        $ldapBind = @ldap_bind($ldapConnection, $ldapUsername, $password);
        if (!$ldapBind)
        {
            // Connection failed, return false
            return null;
        }
        
        // Construct and run LDAP search
        $baseDn = "DC=SDSMT, DC=local";
        $filter = "(&(&(objectClass=user)(objectCategory=person))(samaccountname=".$username."))";
        $ldapSearch = @ldap_search($ldapConnection, $baseDn, $filter);
        if (!$ldapSearch)
        {
            // Search failed, return false
            return null;
        }
        
        if (ldap_count_entries($ldapConnection, $ldapSearch) === 0)
        {
            // Search returned empty, return false
            return null;
        }
        
        $ldapResult = ldap_get_entries($ldapConnection, $ldapSearch);
        $user = new MCSUser($username, $ldapResult[0]['displayname'][0]);
        
        // Unbind from LDAP connection and return results
        ldap_unbind($ldapConnection);
        return $user;
    }
    
    public static function declarePermission($permission)
    {
        // To be implemented
    }
}
