<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of redirect
 *
 * @author aaronr
 */
namespace Moshpit;

class Redirect extends \Moshpit\HttpChat {
    
    private $location;
    
    public function __construct($location,$code=302) {
        //validate $code as redircetion
        parent::__construct($code);
        $this->setLocation($location);
        $this->addHeader("Location: $this->location");
    }
    
    public function setLocation($location) {
        $this->location = $location;
    }
    
    public function getLocation(){
        return $this->location;
    }

    protected function outputContent() {
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title><?php echo $this->getStatus()." ".$this->getCodeText() ?></title>
</head><body>
<h1><?php echo $this->getStatusText() ?></h1>
<p>The document has moved <a href="<?php echo $this->getLocation() ?>">here</a>.</p>
</body></html>
<?php
    }
}

?>
