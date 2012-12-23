<?php
namespace \Moshpit\Html\Forms;
class FormElement {
    private $type;
    private $id;
    private $label;
    private $value;
    
    private $options;
    private $selected;
    
    public function __construct($type, $id, &$value, $label="&nbsp;") {
        $this->type     = $type;
        $this->id       = $id;
        $this->value    = $value;
        $this->label    = $label;
        
        $this->options = array();
        $this->selected = "";
    }
    
    public function addOption($value, $display=NULL, $selected=NULL) {
        if ($this->type != "select") {
            throw new Exception("cannot add options to $this->type");
        }
        
        if ($selected === $value) { $this->selected = $value; }

        if (!$display) { $display = $value; }
        
        $this->options[$value] = $display;
        
    }
    
    public function __destruct() {}
    
    /*final public function getValue($value, $default=NULL, $exists=NULL) {
        if ($this->method == "POST") {
            $array = $_POST;
        } elseif ($this->method == "GET") {
            $array = $_GET;
        } else {
            $array = $_REQUEST;
        }
        
        return Common::getValue($array, $value, $default, $exists);
    }*/
    
    final public function outputInput(){
    ?>
        <div class="form_element">
            <label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label>
    <?php
        switch ($this->type) {
            case 'textarea':?>
                    <<?php 
                        echo $this->type; 
                    ?> id="<?php
                        echo $this->id; 
                    ?>" name="<?php 
                        echo $this->id; 
                    ?>"><?php 
                        echo $this->value;
                    ?></<?php 
                        echo $this->type; 
                    ?>>
                <?php
                break;
            case 'checkbox':?>
                    <input type="<?php 
                        echo $this->type; 
                    ?>" id="<?php
                        echo $this->id; 
                    ?>" name="<?php 
                        echo $this->id; 
                    ?>" <?php 
                        echo $this->value?'checked="checked"':'';
                    ?> />
                <?php
                break;
            case 'select':?>
                    <<?php 
                        echo $this->type; 
                    ?> id="<?php
                        echo $this->id; 
                    ?>" name="<?php 
                        echo $this->id; 
                    ?>">
                        <?php foreach ($this->options as $value => $display) :
                            ?><option <?php
                                if ($this->selected == $value) { echo 'selected="selected" '; } 
                                ?>value="<?php echo $value; ?>"><?php echo $display; ?></option>
                        <?php endforeach; ?>                
                    </<?php 
                        echo $this->type; 
                    ?>>
                    <?php 
                break;
            default:?>
                    <input type="<?php 
                        echo $this->type; 
                    ?>" id="<?php
                        echo $this->id; 
                    ?>" name="<?php 
                        echo $this->id; 
                    ?>" <?php 
                        echo $this->value?'value="'.$this->value.'"':'';
                    ?> />
                <?php
                break;
        }?>
        </div>
    <?php
    }
    
    final public function outputElement() {
    ?>
        <div class="form_element">
            <label for="<?php echo $this->id; ?>"><?php echo $this->label; ?></label>
            <?php echo $this->outputInput() ; ?>
        </div>
    <?php
    }
}
    
?>
