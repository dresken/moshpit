<?php
/**
 * Description of DB
 *
 * @author aaron
 */
namespace Connex;
class DB extends \PDO {
    private static $_dbh;
    
    public static function getConnection($creds=NULL) {
        if(!isset(self::$_dbh)) {
            if ($creds === NULL)
                throw new \Exception("Database credentials must be passed on first access");
            self::$_dbh = new DB($creds);
        }
        return self::$_dbh;
    }
    
    private $server;
    private $username;
    private $password;
    private $database;
    private $driver;    
    
    public function __construct($db_creds) {
        $this->server    = $db_creds->server;
        $this->username  = $db_creds->username;
        $this->password  = $db_creds->password;
        $this->database  = $db_creds->database;
        $this->driver    = $db_creds->driver;
        try {
            parent::__construct($this->driver.':host='.$this->server.';dbname='.$this->database,$this->username,$this->password);
            $this->setAttribute(self::ATTR_ERRMODE, self::ERRMODE_EXCEPTION);
            //Use Buffered query for MySQL?
            if ($this->driver == 'mysql')
                $this->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
        } catch (\Exception $e) {
            throw new \Exception(implode(", ", self::getAvailableDrivers()),0,$e);
        }
    }
    
    /**
     *
     * @return mysqli
     * @throws Exception 
     */
    
    
    protected static function getCredsID($db_creds) {
        return $db_creds->driver.":".$db_creds->server.":".$db_creds->database.":".$db_creds->username;
    }
    
    public function __destruct() {
        //$this->close();
    }
    
    /**
     *
     * @param type $sql
     * @return type
     * @throws Exception 
     * /
    public function execute($sql) {
        $db = $this->getConnection();
        $result = $db->query($sql);
        if (!$result) 
            throw new \Exception('Invalid query: ' . $db->error . '('.$db->errno.') for "'.$sql.'"');
        return $result;
    }
    
    /**
     *
     * @param type $sql
     * @return type 
     * /
    public function prepare($sql) {
        $db = $this->getConnection();
        $stmt = $db->prepare($sql);
        if (!$stmt) 
            throw new \Exception('Invalid query: ' . $db->error . '('.$db->errno.') for "'.$sql.'"');
        return $stmt;
    }/**/
}
?>
