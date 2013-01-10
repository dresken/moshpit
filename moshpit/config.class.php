<?php
namespace Moshpit;
final class Config {
    private static $_config;

    public static function Config($filename=NULL) {
        if(!isset(self::$_config)) {
            self::$_config = new Config();
            if ($filename !== NULL) {
                try {
                    self::$_config->load($filename);
                } catch (\Exception $e) {
                    self::$_config = NULL;
                    throw $e;
                }
            }
        }
        
        return self::$_config;
    }
    
    const CHILD = '->';
    const EQUALS = '=';
    
    private $_VALUE;
            
    public function __construct($value=array()) {
        $this->_VALUE = $value;
    }
    
    public function __set($name, $value) {
        if (array_key_exists($name, $this->_VALUE))
            throw new \Exception("Config option already defined: $name");
        
        $this->add($name, $value);
    }
    
    public function isValue() {
        return ! is_array($this->_VALUE);
    }
    
    private function add($key, $value=array()) {
        $this->_VALUE[$key] = new Config($value);
    }
    
    public function __get($name) {
        if ($name == '_VALUE')
            return $this->_VALUE;
        
        if ($this->isValue())
            throw new \Exception("No further attributes stored");
            
        if (! array_key_exists($name, $this->_VALUE)) {
            $this->add($name);
        }
            
        if ($this->_VALUE[$name]->isValue()) {
            return $this->_VALUE[$name]->_VALUE;
        } else {
            return $this->_VALUE[$name];
        }
    }
    
    public function load($filename) {
        $file = file($filename);
        if (! $file)
            throw new \Exception("File does not exists: ".$filename);

        foreach ($file as $line) {
            $operands = array_map('trim',explode(self::EQUALS, $line,2));
            $names = explode(self::CHILD,$operands[0]);
            $last = array_pop($names);
            $config = $this;
            foreach ($names as $name) {
                $config = $config->$name;
            }
            $config->$last = $operands[1];
        }
    }
    
    public function save($filename) {
        file_put_contents($filename, $this->toString());
    }
    
    public function toArray() {
        if ($this->isValue()) {
            return $this->_VALUE;
        } else {
            ksort($this->_VALUE);
            $result = array();
            foreach ($this->_VALUE as $name => $value) {
                $result[$name] = $value->toArray();
            }
            return $result;
        }
    }
    
    public function toString($parent=array()) {
         if ($this->isValue()) {
            return implode(self::CHILD, $parent).self::EQUALS.$this->_VALUE."\n";
        } else {
            ksort($this->_VALUE);
            $result = '';
            foreach ($this->_VALUE as $name => $value) {
                $new_parent = $parent;
                $new_parent[] = $name;
                $result .= $value->toString($new_parent);
            }
            return $result;
        }
    }
    
    public function __toString() {
        return $this->toString();
    }
}
?>