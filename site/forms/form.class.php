<?php
namespace Site\Forms;
abstract class Form {
    private $errors;
    private $method;
    private $action;
    private $fileUpload;
    private $result;
    
    private $querystring;
    
    private $formElements;
    
    public function __construct($method="POST",$action=NULL) {
        $this->errors = array();
        $this->formElements = array();
        
        $this->setMethod($method);
        $this->setAction($action);
        
        if (\Site\Common::getValue($_SERVER,'REQUEST_METHOD') == 'POST' 
                && empty($_POST) 
                && \Site\Common::getValue($_SERVER,'CONTENT_LENGTH',0) > 0
        ) {
            $this->addError('The server was unable to handle that much POST data ('
                    .\Site\Common::getValue($_SERVER,'CONTENT_LENGTH',0)
                    .' bytes) due to its current configuration');
        }
        
        $this->setResult($this->processForm());
    }
    
    public function __destruct() {}
    abstract public function processForm();
    //abstract public function viewForm();
    
    final public function setFileUpload() {
        $this->fileUpload = 'enctype="multipart/form-data"';
    }
    
    final public function setMethod($method) {
        if ($method != "POST" && $method != "GET")
            throw new Exception('form method is not valid: '.$method);
        
        $this->method = $method;
    }
    
    final public function setAction($action=NULL) {
        if ($action === NULL)
            $this->action = $_SERVER["REQUEST_URI"];
        else
            $this->action = $action;
    }
    
    final protected function setResult($result=NULL) {
        $this->result = $result;
    }

    final public function getResult() {
        return $this->result;
    }
    
    final public function getValue($value, $default=NULL, $exists=NULL) {
        if ($this->method == "POST") {
            $array = $_POST;
        } elseif ($this->method == "GET") {
            $array = $_GET;
        } else {
            $array = $_REQUEST;
        }
        
        return \Site\Common::getValue($array, $value, $default, $exists);
    }

    final public function addError($errors) {
        if (!is_array($errors))
            $errors = array($errors);
        
        foreach ($errors as $error) {
            $this->errors[] = $error;
        }
    }
    
    final public function outputErrors() {
?>
        <div class="error">
<?php 
                foreach($this->errors as $error)
                    echo $error."<br/>"; 
?>
        </div>
<?php
        $this->errors = array();
    }
    
    public function setQuerystring($querystring) {
        $this->querystring = $querystring;
    }
    
    final public function outputForm() {
        $this->addFields();
    ?>
        <div>
            <?php $this->outputErrors(); ?>
            <form <?php echo $this->fileUpload; ?> 
                method="<?php echo $this->method; ?>" 
                action="<?php echo $this->action; 
                    echo $this->querystring?'?'.$this->querystring:'';
                ?>">
                <?php $this->viewForm(); ?>
            </form>
        </div>
    <?php
    }
    
    public function addField($type, $id, &$value, $label="&nbsp;") { 
        $formelement = new FormElement($type, $id, &$value, $label);
        $this->formElements[] = $formelement;
        return $formelement;
    }
    
    abstract protected function addFields();
    
    public function viewForm() {
        foreach($this->formElements as $formElements)
                    $formElements->outputInput(); 
    }
    
    final public function addInput($type, $id, $value, $label="&nbsp;") {
    ?>
        <div class="form_element">
            <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
            <input type="<?php echo $type; ?>" id="<?php echo $id; ?>" name="<?php echo $id; ?>" <?php echo $value?'value="'.$value.'"':'';?> />
        </div>
    <?php
    }
}
    
?>
