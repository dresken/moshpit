<?php
namespace Moshpit;
final class Session {
    public function __construct() {
        if (! session_id())
            session_start();
        //$this->getSession();
    }
    
    public function __destruct() {
        if (session_id() && count($_SESSION) <= 0) {
            session_unset();
            session_destroy();
        }
    }
    
    
    public function clearSession() {
        session_unset();
        session_destroy();
    }
    
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    /*public function set(array $array) {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }*/
    
    public function get($key, $default='') {
        return Common::getValue(&$_SESSION, $key, $default);
    }
}

?>
