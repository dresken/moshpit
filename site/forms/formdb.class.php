<?php
namespace Site\Forms;
abstract class FormDB extends Form {
    private $db;
    private $sql;
    
    

    public function __construct($db_creds, $id_feild = 'id', $method="POST",$action=NULL) {
        $this->db = new \Connex\DB($db_creds);
        $this->sql = $this->generateStatements();
        
        
        $id = $this->getValue($id_feild, FALSE);
        
        parent::__construct($method,$action);
        if ($id && !$this->getResult()) {
            $this->getById($id);
        }
    }
    
    public function __destruct() {}
    //abstract public function processForm();
    //abstract public function viewForm();
    
    /**
     *
     * @return DB 
     */
    public function getDB() {
        return $this->db;
    }
    
    public function getSQL($id) {
        return $this->sql[$id];
    }
    abstract protected function generateStatements();
    abstract protected function getById($id);

}
    
?>
