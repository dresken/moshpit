<?php
namespace Moshpit\Html;

class Element {
    private $name;
    private $attributes;
    private $children;
    private $hack;
    
    public function __construct($name,$hack=NULL) {
        $this->setName($name);
        $this->setHack($hack);
        $this->attributes = array();
        $this->children = array();
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setHack($hack) {
        $this->hack = $hack;
    }

    public function addAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }
    
    /**
     * 
     * @param \Moshpit\Html\Element $child
     * @return \Moshpit\Html\Element
     */
    public function addChild($child) {
        $this->children[] = $child;
        return $this;
    }
    /**
     * 
     * @param type $name
     * @param type $hack
     * @return \Moshpit\Html\Element
     */
    public function createChild($name,$hack=NULL) {
        $child = new Element($name,$hack);
        return $this->addChild($child);
    }
    
    protected function outputStartTag() {
        if (NULL === $this->hack) {
            echo '<'.$this->name;
            foreach ($this->attributes as $name => $value) {
                echo ' '.$name.'="'.$value.'"';
            }
            echo '>';
        } else
            echo $this->hack;
    }
    
    protected function outputEndTag() {
        echo '</'.$this->name.'>';
    }
    
    public function outputElement() {
        $this->outputStartTag();
        foreach ($this->children as $child) {
            if ($child instanceof Element) {
                $child->outputElement();
            } else {
                echo $child;
            }
        }
        $this->outputEndTag();
    }
}

// noend xhtml 
// endtag=TRUE  end
// endtag=FALSE 
?>