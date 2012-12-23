<?php
namespace Site;

abstract class Bones extends \Moshpit\HttpChat {
    private $url;
    private $menu;
    private $head;
    private $title;
    private $titlebase;
    private $errors;
    private $admin;
    private $isProd;
    private $db;
    private $forms;
    private $adminArea;
    
    public function __construct($titlebase='', $forceSSL=FALSE) {
        parent::__construct();
        if ($forceSSL) $this->forceSSL(); else $this->forceNonSSL();
        
        //$this->menu = array();
        $this->head = array();
        $this->errors = array();
        $this->titlebase = $titlebase;
        
        $this->addHead('<meta charset="utf-8">');
        $this->addHead('<meta name="description" content="">');
        $this->addHead('<meta name="viewport" content="width=device-width">');
    }
    
    final protected function addHead($header) {
        $this->head[] = $header;
    }
    
    protected function getHead() {
        $head = $this->head;
        array_splice($head,1,0,'<title>'.$this->getTitle().'</title>');
        return $head;
    }
    
    final protected function setMenu(&$menu) {
        $this->menu = $menu;
    }
    
    protected function getMenu() {
        return $this->menu;
    }
    
    abstract protected function outputHead();
    abstract protected function outputBody();
    abstract protected function outputFooter();
    protected function outputErrors() {
        if (count($this->errors) > 0) {
?>
        <div class="error">
<?php 
                foreach($this->errors as $error)
                    echo $error."<br/>"; 
?>
        </div>
<?php
        }
        $this->errors = array();
    }
    
    //public function __destruct() {
    //    parent::__destruct();
    protected function outputContent() {
        $this->outputHead();
        $this->outputErrors();
        try {
            $this->outputBody();
        } catch (Exception $e) {
        ?>
            <div class="error">
                <?php echo $e->getMessage()."<br/>"; ?>
            </div>
        <?php
        }
        $this->outputFooter();
    }
    
    protected function datestampfile($filename) {
        $filepath = $_SERVER['DOCUMENT_ROOT'].$filename;
        
        if (!file_exists($filepath))
            return $filename;
        
        $dated_filename = preg_split(
                '~^(/?)(styles|js)(/)([^.]*)(\.)(.*)$~',
                $filename,
                -1,
                PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY
        );
        //Insert filetime into array
        array_splice(
                $dated_filename, 
                count($dated_filename)-1, 
                0, 
                date("YmdHis", filemtime($filepath))."."
        );
        
        // Turn the array into a string
        return implode($dated_filename);
    }
    
    protected function addJavascript($src) {
        $this->addHead('<script type="text/javascript" src="'.$src.'"></script>');
    }
    
    protected function addCSS($href) {
        $this->addHead('<link rel="stylesheet" type="text/css" href="'.$href.'" />');
    }
    
    protected function addForm($key, \Site\Forms\Form $form) {
        if (NULL === $this->forms)
            $this->forms = array();
        $this->forms[$key] = $form;
    }
    
    /**
     *
     * @param string $key
     * @return Form 
     */
    protected function getForm($key) {
        return $this->forms[$key];
    }
    
    protected function outputAllForms() {
        foreach ($this->forms as $key => $form) {
            $form->outputForm();
        }
    }
    
    protected function isDev() {
        return ! $this->isProd();
    }
    
    protected function isProd() {
        if (null === $this->isProd)
            $this->isProd = $_SERVER["SERVER_NAME"] == $this->getURL()
                    || $_SERVER["SERVER_NAME"] == 'www.'.$this->getURL();
        return $this->isProd;
    }
    
    /**
     *
     * @return Admin 
     */
    protected function getAdmin() {
        if (null === $this->admin)
            $this->admin = new \Moshpit\Admin();
        return $this->admin;
    }
    
    /**
     *
     * @return DB 
     */
    protected function getDB() {
        if (null === $this->db)
            $this->db = new \Connex\DB(\Config::getDBCreds());
        return $this->db;
    }
    
    protected function setTitle($title="", $overwrite = FALSE) {
        if (! $overwrite)
            $this->title = $this->titlebase.($title!=""?" - ".$title:"");
        else
            $this->title = $title;
    }
    
    protected function getTitle() {
        return $this->title;
    }
    
    protected function getTitleBase() {
        return $this->titlebase;
    }
    
    protected function setURL($url) {
        $this->url = $url;
    }
    
    protected function getURL() {
        return $this->url;
    }
    
    protected function protectedArea($directory, $login) {
        if ($this->adminArea === NULL) $this->adminArea = $directory;
        if (preg_match('|^/'.$directory.'/|',$_SERVER['REQUEST_URI']) 
                && !preg_match('|^/'.$login.'|',$_SERVER['REQUEST_URI']) 
                && !$this->getAdmin()->checkAuth()) {
            throw new \Errors\Redirection('/'.$login.'?referrer='.htmlentities($_SERVER['REQUEST_URI']));
        }
    }
    
    protected function getAdminArea() {
        return$this->adminArea;
    }


    protected function addGoogleAnalytics($id, $protectedArea) {
        if (// Not in admin area
                ! preg_match('|^/'.$protectedArea.'/|',$_SERVER["REQUEST_URI"])
                // Not an admin
                && !$this->getAdmin()->checkAuth()
                //Prod gets the tracking
                && $this->isProd() 
                // Not from home
                && gethostbyname('moshpit.doesntexist.org') != $_SERVER["REMOTE_ADDR"]
        ) {
            $this->addHead('<script type="text/javascript">
              <!--
              var _gaq = _gaq || [];
              _gaq.push(["_setAccount", "'.$id.'"]);
              _gaq.push(["_trackPageview"]);
              (function() {
                var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
                ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
                var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
              })();
              //-->
        </script>'
            );
        }
    }
    
    protected function isMobile() {
    $useragent=  \Moshpit\Common::getValue($_SERVER,'HTTP_USER_AGENT');
    return preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)
            ||
           preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4));
        
    }
}
?>