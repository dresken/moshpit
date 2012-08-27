<?php
/**
 * Description of DB
 *
 * @author aaron
 */
namespace Connex;
class DB {
    private $db_server;
    private $db_user;
    private $db_password;
    private $db_name;
    
    private $mysqli;
    
    public function __construct(array $db_creds,$db_server='localhost',$db_name=null) {
        if (null === $db_name) {
            $db_name = str_replace(".", "_", preg_replace("/^www\./", "", $_SERVER["SERVER_NAME"]));
        }
        $this->db_server = $db_server;
        $this->db_user = $db_creds['username'];
        $this->db_password = $db_creds['password'];
        $this->db_name = $db_name;
    }
    
    /**
     *
     * @return mysqli
     * @throws Exception 
     */
    public function getConnection() {
        if (null === $this->mysqli) {
            $this->mysqli = new \mysqli($this->db_server, $this->db_user, $this->db_password, $this->db_name);
            if ($this->mysqli->connect_error) {
                $errno=$this->mysqli->connect_errno;
                $error=$this->mysqli->connect_error;
                $this->mysqli = NULL;
                throw new Exception ('Connect Error (' . $errno . ') '.$error);
            }
        }
        return $this->mysqli;
    }
    
    public function __destruct() {
        if (null !== $this->mysqli) {
            $this->mysqli->close();
            $this->mysqli = null;
        }
    }
    
    /**
     *
     * @param type $sql
     * @return type
     * @throws Exception 
     */
    public function execute($sql) {
        $db = $this->getConnection();
        $result = $db->query($sql);
        if (!$result) 
            throw new Exception('Invalid query: ' . $db->error . '('.$db->errno.') for "'.$sql.'"');
        return $result;
    }
    
    /**
     *
     * @param type $sql
     * @return type 
     */
    public function prepare($sql) {
        return $this->getConnection()->prepare($sql);
    }
    
    public static function generateStatements($table, array $columns, array $where, array $selectallwhere=NULL, $orderby="", $limit="") {
        if ($orderby != "") 
            $orderby = "ORDER BY `".$orderby."`";
        
        $sql = array(
        'INSERT' => 'INSERT INTO '.$table.' (`'.implode('`, `', $columns).'`) VALUES ('.str_repeat('?, ',count($columns)-1).'?);',
        'SELECT' => 'SELECT '.implode(', ', $where).', `'.implode('`, `', $columns).'` FROM '.$table.' WHERE `'.implode('` = ? AND `', $where).'` = ?;',
        'UPDATE' => 'UPDATE '.$table.' SET `'.implode('`=?, `', $columns).'`=? WHERE `'.implode('` = ? AND `', $where).'` = ?;',
        'DELETE' => 'DELETE FROM '.$table.' WHERE `'.implode('` = ? AND `', $where).'` = ?;',
        'SELECT_ALL' => 'SELECT `'.implode('`, `', $where).'`, `'.implode('`, `', $columns)
            .'` FROM '.$table
            .($selectallwhere?' WHERE `'.implode('` = ? AND `', $selectallwhere).'` = ? ':' ')
            .$orderby.' '.$limit.';',
        '' => ''
        );
        return $sql;
    }
}

?>
