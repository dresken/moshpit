<?php
namespace Moshpit\Auth;
class UserPass extends Auth {
    private $username;
    private $password;
    public function __construct($username="", $password="") {
        $this->username = $username;
        $this->password = $password;
        parent::__construct();
    }
    
    /**
     * Defined in child classes to implement specifics of a login method, should return a username string on success or FALSE on failure 
     * 
     * @return string|false username of successfully logged in user or false if login did not succeed
     */
    protected function run_login() {
        //public function login($username, $plaintext_pw) {
        $username = '';
        $plaintext_pw = '';
        $db = new \Connex\DB(\Config::getDBCreds());
        $stmt = $db->prepare("SELECT password, hashtype FROM login WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        
        if ($db->getConnection()->errno) {
            throw new \Exception("Database Error: ".$this->getDB()->getConnection()->error." (".$this->getDB()->getConnection()->errno.")");
        }
        $hashtype = "";
        $hash_pw = "";
        $stmt->bind_result(
                $hash_pw,
                $hashtype
                );
        $stmt->fetch();
        
        switch ($hashtype) {
            case 'CRYPT':
                $plaintext_pw = crypt($plaintext_pw, $hash_pw);
                break;
            case 'PLAIN':
                break;
            default:
                throw new \Exception("Unknown hashtype: $hashtype");
                break;
        }
        
        if ($plaintext_pw == $hash_pw)
            return $username;
        else
            return FALSE;
    }
    
    /**
     * 
     */
    protected function run_logout() {
        // 
    }    
}

?>
