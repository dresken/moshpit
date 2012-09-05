<?php
namespace Errors;
class GenericError extends \Skeleton {
    private $error;
    public function __construct($error) {
        parent::__construct('Error');
        $this->setStatus(500);
        $this->error = $error;
    }
    
    public static function showerror($page) {
        $error = new \Exception("Class not found $page");
        return new GenericError($error);
    }

    public function outputMainColumn() {
        ?>
            <p>Generic Error</p>
            <?php echo $this->error->getMessage()."<br/><pre>";
            echo $this->error->getTraceAsString();
 var_dump($this->error);
            echo "</pre>";
                    ?>
<?php
    }
}
?>
