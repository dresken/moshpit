<?php
namespace Errors;
class GenericError extends \Skeleton {
    private $error;
    public function __construct($error) {
        $this->error = $error;
        parent::__construct('Error');
    }


    public function outputMainColumn() {
        ?>
            <p>Generic Error</p>
            <?php echo $this->error->getMessage() ?>
<?php
    }
}
?>
