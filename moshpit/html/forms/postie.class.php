<?php
namespace \Moshpit\Html\Forms;

class Postie extends \Skeleton {
    private $sql;
    private $type;
    
    public function __construct($title, $type) {
        $this->sql = \Connex\DB::generateStatements(
                'posts', 
                array(
                    'heading',
                    'draft',
                    'post'
                ), 
                array('id'),
                array('type'),
                'order'
                );
        $this->type = $type;
        
        parent::__construct($title);
    }
    
    //abstract public function processForm();
    //abstract public function viewForm();
    
    public function getSQL($id) {
        return $this->sql[$id];
    }
    
    protected function outputMainColumn() {
        ?><div id="postie_<?php echo $this->type; ?>">
        <?php
        if ($this->getAdmin()->checkAuth()) : ?>
            <div><a href="<?php echo $this->getAdminArea(); ?>/edit?type=<?php echo $this->type; ?>">Add New</a></div>
        <?php 
        endif; 
        
        $stmt = $this->getDB()->prepare($this->sql['SELECT_ALL']);
        $stmt->bind_param("s", $this->type);
        $stmt->execute();
        
        if ($this->getDB()->getConnection()->errno) {
            $this->addError("Error ".$this->getDB()->getConnection()->error." (".$this->getDB()->getConnection()->errno.")");
        }
        
        //initialise
        $id = -1; 
        $heading = "";
        $draft = 1;
        $post = "";
        
        $stmt->bind_result(
                $id, 
                $heading,
                $draft, 
                $post
                );
        while ($stmt->fetch()) {
            if ($draft == 0 || $this->getAdmin()->checkAuth()) :
            ?>
            <div class="section<?php 
                echo $draft==0?'':' admin_display';
                echo $this->getAdmin()->checkAuth()?' admin_section':''
            ?>">
                <h1><?php echo $heading ?></h1>
                <?php if ($this->getAdmin()->checkAuth()) : ?>
                <div><a href="<?php echo $this->getAdminArea(); ?>/edit?id=<?php echo $id ?>">Edit</a></div>
                <?php endif; ?>
                <?php if (trim($post)) : ?>
                <div class="post">
                    <p><?php echo str_replace("\n", '</p><p>', $post) ?></p>
                </div>
                <?php endif; ?>
            </div> <!-- end section -->
        <?php 
            endif;
        }
        ?></div>
                <?php
    }
}
?>
