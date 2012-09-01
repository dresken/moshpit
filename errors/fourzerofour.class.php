<?php
namespace Errors;
class FourZeroFour extends \Skeleton {
    private $page;
    public function __construct($page=null) {
        $this->page = 
            preg_replace('|__|', '.', 
                preg_replace('|^Pages\\\\|', '', $page)
            );
        parent::__construct('Page Not Found');
        $this->setStatus(404);
    }

    public static function pageNotFound($page) {
        if (preg_match('|^Pages\\\\|', $page)) {
            return new FourZeroFour($page);
        }
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