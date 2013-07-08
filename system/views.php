<?php
  /**
   * View manager
   * 
   * @package    MVC_Framework
   * @subpackage System
   * @author     Max Weller <max.weller@teamwiki.net>
   **/
	
  $GLOBALS["globalViewVars"] = array();
  function set_view_var($name, $value) {
    $GLOBALS["globalViewVars"][$name] = $value;
  }
  function get_view_var($name) {
    if (isset($GLOBALS["globalViewVars"][$name])) return $GLOBALS["globalViewVars"][$name];
  }
  
  function load_view($viewName, $data) {
    if (file_exists(VIEW_DIR."/".$viewName.".php")) {
      extract($GLOBALS["globalViewVars"]);
      $_VIEW_DATA = $data;
      extract($data);
      include (VIEW_DIR."/".$viewName.".php");
    } else {
      echo "<div style='border: 1px solid #f00; background: #ffa; padding: 0 15px; margin: 20px 0;'>
      <p><b>An error has occured: Viewloader was unable to load the requested view <code>view/$viewName.php</code>, because the file does not exist.</b></p></div>";
    }
  }
  function get_view($viewName, $data) {
    ob_start();
    load_view($viewName, $data);
    return ob_get_clean();
  }
  
?>