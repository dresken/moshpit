<?php
/**
 * 
 */
namespace Moshpit;
/**
 * 
 */
abstract class Plaintext extends \Moshpit\HttpChat {
    
    /**
     * 
     */
    public function __construct() {
        parent::__construct(200, "text/plain");
    }
}
?>