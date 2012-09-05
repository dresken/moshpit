<?php
namespace Moshpit\Html;

class Comment extends Element {
   
    public function __construct($commented=NULL) {
        parent::__construct('comment');
        $this->addChild($commented);
    }
    
    protected function outputStartTag() {
        echo '<!-- ';
    }
    
    protected function outputEndTag() {
        echo ' -->';
    }
}
?>