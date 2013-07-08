<?php

class UserModel extends Model {
	
	public $name;
	public $id;
	public $rights; //friends, data, apps
	
	public function __construct() {
		parent::__construct();
    $this->DB = load_model("database");
	}
  
	public function loadUserByID($id) {
		$this->sql('SELECT * FROM users WHERE id=%d', $id);
    $ret=$this->get();
    if ($ret) { $this->userdata=$ret; }
	}
  
	public function auth($name,$pw) {
		$this->DB->sql("SELECT * FROM users WHERE name='".mysql_real_escape_string($name)."' AND password='".$this->passHash($name,$pw)."'");
    $dt=$this->DB->get();
    return $dt;
	}
  
  public function getPermission($id,$namespace) {
    $this->DB->query("SELECT permission FROM user_permissions WHERE id=%d AND namespace='%s'",$id,$namespace);
    return $this->DB->get_first_value();
  }
  
  public function getPermissions($id) {
    $this->DB->sql("SELECT * FROM user_permissions WHERE id=%d",$id);
    
    return $this->DB->getlist();
  }
}

?>