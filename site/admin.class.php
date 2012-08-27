<?php
namespace Site;
class Admin {
    private $username;
    private $login;
    private $session;
    
    public function __construct() {
        $this->session = new Session();
        $this->username = $this->session->get('username');
        $this->login = $this->session->get('login');
    }
    
    public function checkAuth() {
        return $this->login;
    }
    
    public function login($username, $plaintext_pw) {
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
                $this->login = crypt($plaintext_pw, $hash_pw) == $hash_pw;
                break;
            case 'PLAIN':
                $this->login = $plaintext_pw == $hash_pw;
                break;
            default:
                throw new \Exception("Unknown hashtype: $hashtype");
                break;
        }
        
        
        if ($this->login) {
            $this->username = $username;
            $this->session->set('username', $this->username);
            $this->session->set('login', $this->login);
        } else {
            $this->session->clearSession();
        }
        return $this->login;
    }
    
    public function logout() {
        $this->username = '';
        $this->login = FALSE;
        $this->session->set('username', $this->username);
        $this->session->set('login', $this->login);
        $this->session->clearSession();
        return TRUE;
    }
}

?>
