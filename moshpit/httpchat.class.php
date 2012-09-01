<?php 
/**
 * Root class in any communication 
 * 
 * @package MoshpitEngine
 */
namespace Moshpit;
/**
 * Root class in any communication. Constructs a channel to communicate on.
 * 
 * Destructor ensures a response is sent back to the client. 
 * No need to explicitly send a response in an inherited class
 * 
 * @package MoshpitEngine
 */
abstract class HttpChat {
    public static $HTTP  = 'http://';
    public static $HTTPS = 'https://';

    private $scheme;
    private $status;
    private $protocol;
    private $sent;
    private $headers;
    private $contentType;
    
    private $errors;


    /**
     * 
     * @param int $code
     */
    public function __construct($status=200,$contentType="text/html") {
        $this->setStatus($status);
        $this->setContentType($contentType);
        $this->setProtocol();
        
        $this->scheme = $this->getValue(&$_SERVER, 'HTTPS', self::$HTTP, self::$HTTPS);
        
        $this->errors = array();
        $this->headers = array();
        $this->sent = false;
    }
    
    /**
     * Destructor ensure the response has been sent
     */
    public function __destruct() {
        //if we are closing down make sure we send
        try {
            $this->send();
        } catch (\Exception $error) {
            header('HTTP/1.1 500 Internal Server Error');
            echo $error->getMessage();
        }
    }
    
    public function getValue(array $array, $value, $default=NULL, $exists=NULL) {
        return Common::getValue($array, $value, $default, $exists);
    }
    
    /**
     * Send a response to the client. Can only be invoked once.
     * 
     * @return boolean
     */
    final public function send() {
        //you can only send a responce once
        if (!$this->sent) {
            $this->addHeader($this->getProtocol() . ' ' . $this->getStatus() . ' ' . $this->getStatusText());
            $this->addHeader('Content-Type:'.$this->getContentType());
            foreach($this->getHeaders() as $header)
                header($header);
            $this->outputContent();
            $this->sent = TRUE;
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Add to response headers
     * 
     * @param type $header
     */
    final protected function addHeader($header) {
        $this->headers[] = $header;
    }
    
    /**
     * Return header array
     * 
     * @return array
     */
    final protected function getHeaders() {
        return $this->headers;
    }
    
    /**
     * outputs Contents part of request
     * 
     * should echo output
     */
    abstract protected function outputContent(); 
    
    /**
     * 
     * @param int $status
     */
    final public function setStatus($status) {
        $this->status = $status;
    }
    
    /**
     * 
     * @return int
     */
    final public function getStatus() {
        return $this->status;
    }
    
    final public function setContentType($contentType) {
        $this->contentType = $contentType;
    }
    
    final public function getContentType() {
        return $this->contentType;
    }
    
    
    /**
     * 
     * @param int|string $protocol
     */
    final public function setProtocol($protocol=NULL) {
        if ($protocol===NULL)
            $this->protocol=(isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        else
            switch ($protocol) {
                case "1.1":
                case "HTTP/1.1":
                    $this->protocol="HTTP/1.1";
                    break;
                case "1.0":
                case "HTTP/1.0":
                default :
                    $this->protocol="HTTP/1.0";
                    break;   
            }
    }
    
    /**
     * 
     * @return string
     */
    final public function getProtocol() {
        return $this->protocol;
    }
    
    /**
     * 
     * @return string
     * @throws Exception
     */
    final public function getStatusText() {
        $code = $this->getStatus();
        $protocol = $this->getProtocol();
        
        switch ($code) {
            
            case 200: $text = 'OK'; break;
            case 201: $text = 'Created'; break;
            case 202: $text = 'Accepted'; break;
           
            case 204: $text = 'No Content'; break;
            
            case 300: $text = 'Multiple Choices'; break;
            case 301: $text = 'Moved Permanently'; break;
            
            case 303: $text = 'See Other'; break;
            case 304: $text = 'Not Modified'; break;
            case 305: $text = 'Use Proxy'; break;
            
            case 400: $text = 'Bad Request'; break;
            case 401: $text = 'Unauthorized'; break;
            case 402: $text = 'Payment Required'; break;
            case 403: $text = 'Forbidden'; break;
            case 404: $text = 'Not Found'; break;
            case 405: $text = 'Method Not Allowed'; break;
            case 406: $text = 'Not Acceptable'; break;
            case 407: $text = 'Proxy Authentication Required'; break;
            case 408: $text = 'Request Time-out'; break;
            case 409: $text = 'Conflict'; break;
            case 410: $text = 'Gone'; break;
            case 411: $text = 'Length Required'; break;
            case 412: $text = 'Precondition Failed'; break;
            case 413: $text = 'Request Entity Too Large'; break;
            case 414: $text = 'Request-URI Too Large'; break;
            case 415: $text = 'Unsupported Media Type'; break;
            case 500: $text = 'Internal Server Error'; break;
            case 501: $text = 'Not Implemented'; break;
            case 502: $text = 'Bad Gateway'; break;
            case 503: $text = 'Service Unavailable'; break;
            case 504: $text = 'Gateway Time-out'; break;
            case 505: $text = 'HTTP Version not supported'; break;
            default:
                if ($protocol=='HTTP/1.1') {
                    switch ($code) {
                        case 100: $text = 'Continue'; break;
                        case 101: $text = 'Switching Protocols'; break;
                        case 203: $text = 'Non-Authoritative Information'; break;
                        case 205: $text = 'Reset Content'; break;
                        case 206: $text = 'Partial Content'; break;
                        case 302: $text = 'Found'; break;
                        case 307: $text = 'Temporary Redirect'; break;
                        default:
                            throw new \Exception('Unknown http status code "' . htmlentities($code) . '"');
                            //$text = "Unknown";
                            break;
                    }
                } elseif ($protocol=='HTTP/1.0') {
                    switch ($code) {
                        case 302: $text = 'Moved Temporarily'; break;
                        default:
                            throw new \Exception('Unknown http status code "' . htmlentities($code) . '"');
                            //$text = "Unknown";
                            break;
                    }
                }
                
            }
        
        return $text;
        
        /*HTTP 1.0
           | "301"   ; Moved Permanently
           | "302"   ; Moved Temporarily
           | "304"   ; Not Modified
           | "400"   ; Bad Request
           | "401"   ; Unauthorized
           | "403"   ; Forbidden
           | "404"   ; Not Found
           | "500"   ; Internal Server Error
           | "501"   ; Not Implemented
           | "502"   ; Bad Gateway
           | "503"   ; Service Unavailable
         */
    }
    
    protected function setURL($url) {
        $this->url = $url;
    }
    
    protected function getURL() {
        return $this->url;
    }
    
    private function forceScheme($scheme) {
        if ($this->scheme != $scheme) {
            $querystring = $this->getValue(&$_SERVER, 'QUERY_STRING'); 
            if (strlen($querystring) > 0) 
                $querystring = '?'.$querystring;
            
            throw new \Errors\Redirection($scheme.$_SERVER["HTTP_HOST"].$_SERVER['REDIRECT_URL'].$querystring);
        }
    }
    
    final protected function forceSSL() {
        $this->forceScheme(self::$HTTPS);
    }
    
    final protected function forceNonSSL() {
        $this->forceScheme(self::$HTTP);
    }
    
    final protected function addError($errors) {
        if (!is_array($errors))
            $errors = array($errors);
        
        foreach ($errors as $error) {
            $this->errors[] = $error;
        }
    }
    
    protected function outputErrors() {
        if (count($this->errors) > 0) {
            echo "Errors:\n";
            foreach($this->errors as $error)
                echo $error."\n"; 
        }
        $this->errors = array();
    }
}
?>