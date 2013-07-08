<?php
	
$loadedFormatters = array();
  
function load_formatter($model,$newInstance=false) { global $loadedFormatters;
  if (file_exists(FORMATTER_DIR."/".$model.".php")==false) { return false; }
  require_once FORMATTER_DIR."/".$model.".php";
  $class = ucfirst($model)."Formatter";
  
  if ($newInstance==true) { return new $class(); }
  if (!isset($loadedFormatters[$class])) { $loadedFormatters[$class] = new $class(); }
  return $loadedFormatters[$class];
}
  
interface FormatterTemplate {
  public function format($instance,$data);
}

?>