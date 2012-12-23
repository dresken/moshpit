<?php
namespace Moshpit\Auth;
class RemoteUser extends Auth {
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Defined in child classes to implement specifics of a login method, should return a username string on success or FALSE on failure 
     * 
     * @return string|false username of successfully logged in user or false if login did not succeed
     */
    protected function run_login() {
        if (isset($_SERVER["REMOTE_USER"]))
            return $_SERVER["REMOTE_USER"];
        else
            return FALSE;
    }
    
    /**
     * 
     */
    protected function run_logout() {
        // how to REMOTE_USER 
    }    
}

?>
