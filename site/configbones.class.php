<?php
namespace Site;
abstract class ConfigBones {
    
    public function __construct() {}
    
    abstract static public function getDBCreds();
}
?>