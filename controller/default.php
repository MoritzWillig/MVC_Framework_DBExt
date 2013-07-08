<?php
/**
 * Default controller
 * 
 * @package    TrueApps
 * @subpackage Controllers
 * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <>
**/
	
class DefaultController extends Controller {
  
  function index() {
    // make first menu item current, as this can be invoked with GET /
    echo "Its alive<br>";
    
    //$this->menu_check_current($this->template_vars["Main_Menu"], "");
    
    //$this->template_vars["Content"] = get_view("pages_index", array());
    
    //$this->display_layout();
  }
}
  
?>