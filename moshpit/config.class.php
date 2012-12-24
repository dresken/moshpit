<?php
namespace Moshpit;
final class Config {
    private $_CONFIG;
    private $_VALUE;
            
    public function __construct($value=NULL) {
        $this->_CONFIG = array();
        $this->_VALUE = $value;
    }
    
    public function __set($name, $value) {
        $this->_CONFIG[$name] = new Config($value);
    }
    
    public function __get($name) {
        if ($name == '_VALUE') {
            return $this->_VALUE;
        } elseif (array_key_exists($name, $this->_CONFIG)) {
            return $this->_CONFIG[$name]->_VALUE;
        }

        /*$trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
         */
        return NULL;
    }
    

}
?>