<?php
namespace Site\Forms;
abstract class FormDB extends Form {
    private $id;
    private $db;
    private $sql;
    
    public function __construct($method="POST", $action=NULL, $id_field = 'id') {
        $this->db = \Connex\DB::getConnection();
        $this->sql = $this->generateStatements();
        $id = $this->getValue($id_field, FALSE);
        parent::__construct($method,$action);
        
        if ($id && !$this->getResult())
            $this->id = $this->getById($id);
        
        //if we have a valid id redirect
        if ($this->id)
            $this->setAction(\Moshpit\Common::getValue($_SERVER, 'REDIRECT_URL').'?'.$id_field.'='.$this->id);
    }
    
    public function __destruct() {}
    
    public function __set($name, $value) {
        throw new \Exception("Variable $name cannot be set");
    }
    
    public function __get($name) {
        if (! property_exists($this, $name))
            throw new \Exception("Variable $name does not exist");
        return $this->$name;
    }
    
    public function updateID() {
        $this->id = $this->db->lastInsertId();
    }

    //abstract public function processForm();
    //abstract public function viewForm();
    
    abstract protected function generateStatements();
    abstract protected function getById($id);

}
    
?>
