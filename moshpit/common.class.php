<?php
/**
 * Filled with crappy common static functions used everywhere
 *
 * @author aaron
 */
namespace Moshpit;

final class Common {
    /**
     * Safe get value from array function
     * @param array $array
     * @param mixed $value
     * @param mixed $default
     * @param mixed $exists
     * @return mixed 
     */
    public static function getValue(array $array, $value, $default=NULL, $exists=NULL) {
        if (isset($array[$value])) {
            if ($exists) {
                return $exists;
            }
            return $array[$value];
        }
        return $default;
    }
    
    /**
     * Redirection function
     * @param type $location
     * @param boolean $redirect 
     */
    public static function redirect($location) {
        if (NULL !== $location) {
            throw new \Errors\Redirection($location);
        }
    }
    
    /**
     *
     * @param type $needle
     * @param type $haystack
     * @return type 
     */
    public static function startsWith($needle, $haystack) {
        return preg_match('/^'.preg_quote($needle)."/", $haystack);
    }
    
    /**
     *
     * @param type $needle
     * @param type $haystack
     * @return type 
     */
    public static function endsWith($needle, $haystack) {
        return preg_match("|".preg_quote($needle) .'$|', $haystack);
    }
    
    
    
    
    
    
    
    
}

?>
