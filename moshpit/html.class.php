<?php
namespace Moshpit;

abstract class HTML extends \Moshpit\HttpChat {
    private $doctype;
    private $html;
    private $head;
    private $body;
    
    public function __construct() {
        parent::__construct();
        $this->doctype = '<!doctype html>';
        $this->html = new H5bp\Html('en');
        $this->head = new Html\Element('head');
        $this->body = new Html\Element('body');
        
        $this->html->addChild($this->head);
        $this->html->addChild($this->body);
    }
    
    final protected function write($line) {
        echo $line."\n";
    }
    
    final protected function outputDocType() {
        $this->write($this->doctype);
    }
    
    /**
     * 
     * @return \Moshpit\Html\Element
     */
    final protected function getBody() {
        return $this->body;
    }
    
    protected function outputContent() {
        $this->outputDocType();
        $this->html->outputElement();
    }
}
?>