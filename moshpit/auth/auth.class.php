<?php
namespace Moshpit\Auth;
abstract class Auth {
    private $username;
    private $session;
    
    public function __construct($checkAuth=FALSE) {
        $this->session = new \Moshpit\Session();
        $this->username = $this->session->get('username');
        if ($checkAuth && !$this->checkAuth())
            $this->login();
    }
    
    final public function login() {
        $username = $this->run_login();
        
        if (is_string($username) && $username !== '') {
            $this->username = $username;
            $this->session->set('username', $this->username);
        }
        return $this->checkAuth();
    }

    final public function getUsername() {
        if (!$this->checkAuth())
            throw new \Exception("No user is authenticated");
        return $this->username;
    }
    
    final public function checkAuth() {
        return (is_string($this->username) && $this->username !== '');
    }
    
    /**
     * Defined in child classes to implement specifics of a login method, should return a username string on success or FALSE on failure 
     * 
     * @return string|false username of successfully logged in user or false if login did not succeed
     */
    abstract protected function run_login();
    
    /**
     * 
     */
    abstract protected function run_logout();
    
    private function clearAuth() {
        $this->username = NULL;
        $this->session->clear('username');
    }
    
    final public function logout() {
        $this->run_logout();
        $this->clearAuth();
        return !$this->checkAuth();
    }
}

?>
