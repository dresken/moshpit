<?php
/**
 * 
 */
namespace Moshpit;
/**
 * 
 */
abstract class Googleverify extends \Moshpit\Plaintext {
    private $id;
    /**
     * 
     * @param string $id
     */
    public function __construct($id) {
        parent::__construct();
        $this->id = $id;
    }
    
    /**
     * 
     */
    final protected function outputContent() {
?>
google-site-verification: google<?php echo $this->id; ?>.html
<?php
    }
}
?>