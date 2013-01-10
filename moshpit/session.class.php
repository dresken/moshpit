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
    
    private function selfdestruct($key) {
        if (!isset($_SESSION['selfdestruct'])) {
            $_SESSION['selfdestruct'] = array();
        }
        $_SESSION['selfdestruct'][$key] = TRUE;
    }
    
    public function set($key, $value, $selfdestruct=FALSE) {
        $_SESSION[$key] = $value;
        if ($selfdestruct) 
            $this->selfdestruct($key);
    }
    
    public function clear($key) {
        if (isset($_SESSION[$key])) 
            unset($_SESSION[$key]);
        
        if (isset($_SESSION['selfdestruct'][$key])) 
            unset($_SESSION['selfdestruct'][$key]);
    }
    /*public function set(array $array) {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
    }*/
    
    public function get($key, $default='') {
        $result = Common::getValue($_SESSION, $key, $default);
        if (isset($_SESSION['selfdestruct'][$key])) 
            $this->clear($key);
        return $result;
    }
}

?>
