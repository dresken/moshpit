<?php
namespace Errors;
class FourZeroFour extends \Skeleton {
    private $page;
    public function __construct($page=null) {
        $this->page = $page;
        parent::__construct('Page Not Found');
        $this->setCode(404);
    }

    public static function pageNotFound($page) {
        return new FourZeroFour($page);
    }

    public function outputMainColumn() {
    ?>
        <h1>Page not found</h1>
        <p>You have tried to access a page that does not exist.</p>
        <p>Please check the URL or select from the menus above.</p>
    <?php
        echo $this->page?$this->page:"";
    }
}
?>