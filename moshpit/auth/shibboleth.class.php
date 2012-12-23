<?php
namespace Moshpit\Auth;
class Shibboleth extends RemoteUser {
    private $loginurl;
    private $logouturl;
    private $targeturl;
    
    public function __construct($loginurl='/Shibboleth.sso/Login', $logouturl='/Shibboleth.sso/Logout') {
        parent::__construct();
        $this->loginurl = $loginurl;
        $this->logouturl = $logouturl;
        $this->targeturl = \Moshpit\Common::getValue($_SERVER, 'REDIRECT_URL', '/');
    }
    
    /**
     * Defined in child classes to implement specifics of a login method, should return a username string on success or FALSE on failure 
     * 
     * @return string|false username of successfully logged in user or false if login did not succeed
     */
    protected function run_login() {
        $username = parent::run_login();
        if (! $username)
            //Redirect to Login
            throw new \Errors\Redirection($this->loginurl.'target='.$this->targeturl);
        return $username;
    }
    
    /**
     * 
     */
    protected function run_logout() {
        parent::run_logout();
        //Redirect to Logout
        throw new \Errors\Redirection($this->logouturl);
    }    
}

?>
