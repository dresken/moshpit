<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Moshpit\H5bp;
/**
 * Description of html
 *
 * @author aaronr
 */
class Html extends \Moshpit\Html\Element{
    private static $NAME = 'html';

    private $ieversions;
    private $classes;
/*
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
*/  
    public function __construct($lang=NULL) {        
        $this->ieversions = array(
            'lt IE 7',
            'IE 7',
            'IE 8',
            'gt IE 8'
        );
        $this->classes = array(
            'no-js',
            'lt-ie9',
            'lt-ie8',
            'lt-ie7'
        );
        parent::__construct(self::$NAME);
        
        $hack = '';
        $size = count($this->ieversions);
        for ($i = 0; $i < $size; $i++) {
            $value = $this->ieversions[$i];
            $hack .= '<!--[if '.$value.']>';
            if ($i == $size-1)
                $hack .= '<!-->';
            $hack .= ' <'.$this->getName().' class="'.implode(" ", $this->classes).'"';
            if (NULL !== $lang) 
                $hack .= ' lang="'.$lang.'"';
            $hack .= '> ';
            if ($i == $size-1)
                $hack .= '<!--';
            $hack .= '<![endif]-->'."\n";
            
            array_pop($this->classes);
        }
        
        $this->setHack($hack);
    }
}

?>
