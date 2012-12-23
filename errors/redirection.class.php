<?php
/**
 * RedirectionException is used so that when a redirection is issued in code it will be dealt with
 * - if not caught before and ignored or otherwise
 * - will be caught by bootstrap.php and actioned
 * 
 * As not page output occurs until deconstructor - header should not have been sent
 *
 * @author aaronr
 */
namespace Errors;
class Redirection extends \Exception
{
    private $location;
    private $response_code;
    
    public function __construct($location, $response_code=302, $message=null, $code = 0, \Exception $previous = null) {
        $this->location = $location;
        $this->response_code = $response_code;
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}. $this->response_code to $this->location\n";
    }

    /**
     * Redirection function
     * @param type $location
     * @param boolean $redirect 
     */
    public function redirect() {
        return new \Site\Redirect($this->location,$this->response_code);
    }
}

?>
