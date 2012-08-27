<?php
namespace Site;
class MenuItem {
    private $link;
    private $url;
    private $submenu;
    
    public function __construct($link='', $url='') {
        $this->link = $link;
        $this->url = $url;
        $this->submenu = array();
    }
    
    public function addSubmenu($link, $url='') {
        $submenu = new MenuItem($link, $url);
        $this->submenu[] = $submenu;
        return $submenu;
    }
    
    public function addSubmenuObj(MenuItem $menu) {
        $this->submenu[] = $menu;
    }
    
    public function outputMenu() {
        ?>
        <div id="menu" class="container">
        <?php $this->outputSubmenu(); ?>
        </div><!-- end menu -->    
        <?php
    }
    
    public function outputSubmenu() {
        if (count($this->submenu) > 0) {
        ?>
            <ul<?php //echo $this->link==""?' id="nav"':'' ?>>
            <?php 
                foreach ($this->submenu as $submenu) {
                    $submenu->outputMenuItem();
                }
            ?>
            </ul>
        <?php 
        }
    }
    
    public function outputMenuItem() {
    ?>
        <li>
            <a <?php echo Common::getValue($_SERVER, 'REDIRECT_URL', '')==$this->url?'class="selected_page"':'' ?> 
                href="<?php echo $this->url; ?>"><?php echo $this->link; ?></a>
            <?php $this->outputSubmenu(); ?>
        </li>
    <?php
    }
}
?>