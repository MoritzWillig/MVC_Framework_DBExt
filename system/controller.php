<?php
  /**
   * Abstract base class for Controllers
   * 
   * @package    MVC_Framework
   * @subpackage Abstracts
   * @author     Max Weller <max.weller@teamwiki.net>
   **/
	
  class Controller {
    
    function display_layout($title="",$content="",$css=array()) {
      $this->template_vars["title"]=$title;
      if ($content!="") { $this->template_vars["Content"]=$content; }
      
      $a=array(URL_PREFIX."content/css/main.css");
      for ($i=0; $i<count($css); $i++) {
        if (file_exists(CONTENT_DIR."/css/".$css[$i].".css")) {
          array_push($a,URL_PREFIX."content/css/".$css[$i].".css");
          }
      }
      $this->template_vars["addCSS"]=$a;
      load_view("layout", $this->template_vars);
    }
    
    protected function require_login() {
      if (! $this->Session->isLoggedIn()) {
        header("Location: ".URL_PREFIX."user/login");
        exit;
      }
    }
    
    public function display_error($errString) {
      $title="";
      $this->display_layout($title,$errString);
    }
    
  }
  
?>