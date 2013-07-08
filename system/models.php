<?php
  /**
   * Model loader
   * 
   * @package    MVC_Framework
   * @author     Max Weller <max.weller@teamwiki.net>
   * @editor     Moritz Willig <moritz.willig@gmail.com>
   * Added newInstance parameter
   **/
	
  $loadedModels = array();
  
  function load_model($model,$newInstance=false) { global $loadedModels;
    require_once MODEL_DIR."/".$model.".php";
    $class = ucfirst($model)."Model";
    
    if ($newInstance==true) { return new $class(); }
    if (!isset($loadedModels[$class])) { $loadedModels[$class] = new $class(); }
    return $loadedModels[$class];
  }
  
  function require_model($model) {
    global $loadedModels;
    require_once MODEL_DIR."/".$model.".php";
  }

	class Model {
    public function __construct() {}
  }
?>