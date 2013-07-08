<?php
class SessionModel extends Model  {
  
	public function __construct() {
		parent::__construct();
    
    ini_set('session.name','rolDeSID');
    session_name('rolDeSID');
    $host_parts = explode(".",$_SERVER["HTTP_HOST"]);
    $domain="." . $host_parts[count($host_parts)-2] . "." . $host_parts[count($host_parts)-1];
    session_set_cookie_params (630720000, "/", $domain); //3600*24*365*20
    session_start();
    	
    if (!isset($_SESSION['login'])) { $_SESSION['login']=false; }
    if (!isset($_SESSION['userdata'])) { $_SESSION['userdata']=array('id'=>-1, 'name'=>''); }
    
    $this->User = load_model("user");
	}
	
	public function login($userdata) {
		$_SESSION['login']=true;
    $_SESSION['userdata']=$userdata;

	}
	
	public function logout() {
		$_SESSION['login']=false;
    $_SESSION['userdata']=array('id'=>-1, 'name'=>'');
	}
  
	public function isLoggedIn() {
		return $_SESSION['login'];
	}
  
	public function isAdmin() {
    if (!$this->isLoggedIn()) { return false; }
    $perm=$this->User->getPermission($_SESSION['userdata']["id"],LOCAL_NAMESPACE);
    if ($perm=="admin") { return true; } else { return false; }
	}
  
  public function isEditor() {
    if (!$this->isLoggedIn()) { return false; }
    $perm=$this->User->getPermission($_SESSION['userdata']["id"],LOCAL_NAMESPACE);
    if (($perm=="editor") or ($perm=="admin")) { return true; } else { return false; }
  }
  
  public function userData() {
    return $_SESSION['userdata'];
  }
	
  
  
}
?>