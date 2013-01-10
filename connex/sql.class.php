<?php
/**
 * Description of SQL
 *
 * @author Aaron Howell
 */
namespace Connex;
class SQL {
    
    private $table;
    private $columns;
    private $where;
    
    public function __construct($table, array $columns, array $where) {
        $this->table = $table;
        $this->columns = $columns;
        $this->where = $where;
    }
    
    public function __destruct() {}
    
    public function __set($name, $value) {
        throw new \Exception("Statement '".$name."' cannot be set");
    }
    
    public function __get($name) {
        switch ($name) {
            case 'INSERT':
                return 'INSERT INTO '.$this->table.' (`'.implode('`, `', $this->columns).'`)'
                    .' VALUES ('.str_repeat('?, ',count($this->columns)-1).'?);';
            case 'SELECT':
                return 'SELECT `'.implode('`, `', array_merge($this->where, $this->columns)).'` FROM '.$this->table
                    .' WHERE `'.implode('` = ? AND `', $this->where).'` = ?;';
            case 'UPDATE':
                return 'UPDATE '.$this->table.' SET `'.implode('`=?, `', $this->columns).'`=?'
                    .' WHERE `'.implode('` = ? AND `', $this->where).'` = ?;';
            case 'DELETE':
                return 'DELETE FROM '.$this->table.' WHERE `'.implode('` = ? AND `', $this->where).'` = ?;';
            default :
                throw new \Exception("Statement '".$name."' does not exist");
        }
    }
    
    public function getSelectAll(array $selectallwhere=NULL, $orderby="", $limit="") {
        if ($orderby != "") 
            $orderby = "ORDER BY `".$orderby."`";
        
        $sql = array(
        
        'SELECT_ALL' => 'SELECT `'.implode(', ', array_merge($this->where, $this->columns))
            .'` FROM '.$this->table
            .($selectallwhere?' WHERE `'.implode('` = ? AND `', $selectallwhere).'` = ? ':' ')
            .$orderby.' '.$limit.';',
        '' => ''
        );
        return $sql;
    }
}

?>
