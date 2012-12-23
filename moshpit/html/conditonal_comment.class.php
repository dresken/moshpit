<?php
//<!--[if gt IE 6]><!-->
//This code displays on non-IE browsers and on IE 7 or higher.
//<!--<![endif]-->
namespace Moshpit\Html;

class Conditonal_Comment extends Comment {
    private $conditional;
    private $exposed;
    
    public function __construct($conditonal,$commented=NULL,$exposed=FALSE) {
        parent::__construct($commented);
        $this->setConditional($conditonal);
        $this->setExposed($exposed);
        $this->addChild($commented);
    }
    public function setConditional($conditonal) {
        $this->conditional = $conditonal;
    }
    public function setExposed($exposed=TRUE) {
        $this->exposed = $exposed;
    }
    
    protected function outputStartTag() {
        echo '<!--[if '.$this->conditional.']>';
        if ($this->exposed)
            echo '<!-->';
        echo ' ';

    }
    
    protected function outputEndTag() {
        echo ' ';
        if ($this->exposed)
            echo '<!--';
        echo '<![endif]-->';
    }
}
?>