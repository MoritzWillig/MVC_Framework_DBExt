<?php
  define("ROOT"           ,'/srv/homepages/rise-of-light.de/rewrite/www');
  define('URL_PREFIX'     ,'http://rise-of-light.de/');
  define('URI_PREFIX'     ,'/rewrite');
  define('LOCAL_NAMESPACE',URL_PREFIX);
  define('CONFIG_FILE'    ,ROOT.'/../config.ini');
  
  define("CONTROLLER_DIR", ROOT."/controller");
  define("MODEL_DIR"     , ROOT."/model");
  define("FORMATTER_DIR" , ROOT."/formatter");
  define("VIEW_DIR"      , ROOT."/view");
  define("CONTENT_DIR"   , ROOT."/content");
  
  require_once ROOT."/system/consts.php"; 
  require_once ROOT."/system/routing.php"; 
  require_once ROOT."/system/views.php";
  require_once ROOT."/system/models.php";
  require_once ROOT."/system/controller.php";
  require_once ROOT."/system/formatter.php";
  
  load_controller();
?>