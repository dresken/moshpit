<?php 
/***
 * 
 */
namespace Connex;

abstract class MoshHttp {
    private $code;
    private $protocol;
    private $sent;
    private $headers;
    
    public function __construct($code=200) {
        $this->setCode($code);
        $this->setProtocol();
        $this->headers = array();
        $this->sent = false;
    }
    
    public function __destruct() {
        $this->send();
    }
    
    final public function send() {
        if (!$this->sent) {
            $this->addHeaders($this->getProtocol() . ' ' . $this->getCode() . ' ' . $this->getCodeText());
            foreach($this->getHeaderss() as $header)
                header($header);
            $this->outputContent();
            $this->sent = true;
        }
    }
    
    final protected function addHeaders($header) {
        $this->headers[] = $header;
    }
    
    final protected function getHeaderss() {
        return $this->headers;
    }
    
    abstract protected function outputContent(); 
    
    final public function setCode($code) {
        $this->code = $code;
    }
    
    final public function getCode() {
        return $this->code;
    }
    
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
    
    final public function getProtocol() {
        return $this->protocol;
    }
    
    
    final public function getCodeText() {
        $code = $this->getCode();
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
                            throw new Exception('Unknown http status code "' . htmlentities($code) . '"');
                            break;
                    }
                } elseif ($protocol=='HTTP/1.0') {
                    switch ($code) {
                        case 302: $text = 'Moved Temporarily'; break;
                        default:
                            throw new Exception('Unknown http status code "' . htmlentities($code) . '"');
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
}
?>