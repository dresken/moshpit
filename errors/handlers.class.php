<?php
namespace Errors;
class Handlers {
    
    public static function defaultTypeHintCatcher($code, $message, $file, $line) {
        if (error_reporting() & $code) {
            if ($code == E_RECOVERABLE_ERROR) { // Scalar Type-Hinting patch.
                $regexp = '/^Argument (\d)+ passed to (.+) must be an instance of (?P<hint>.+), (?P<given>.+) given/i';
                if (preg_match($regexp, $message, $match)) {
                    $given = $match[ 'given' ] ;
                    $hint  = end(explode('\\', $match[ 'hint' ])); // namespaces support.
                    if ($hint == $given)
                        return TRUE;
                }
            }
            return FALSE;
        }
    }
}

?>
