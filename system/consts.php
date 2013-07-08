<?php

/**
 * Global constances and output handling
 * 
 * @package    MVC_Framework DBExt
 * @subpackage System
 * @author     Moritz Willig <moritz.willig@gmail.com>
**/

class GlobalConsts {
  
  private static $inst;

  public static function loadConfig() {
    $init=parse_ini_file(CONFIG_FILE,true);
    if ($init) {
      foreach ($init as $key => $value) { self::$inst->$key = $value; }
    }
  }
  
  public static function init() {
    self::$inst=new stdClass();
    self::loadConfig();
  }
  
  public static function get($s) {
    if (isset(self::$inst->{$s})) {
      return self::$inst->{$s};
    } else {
      return null;
    }
  }
  
  public static function set($s,$v) {
    self::$inst->{$s}=$v;
  }
}

/**
 * Output handling
**/

class Instance {
  private static $error=array();
  private static $mime=array();
  public static $formatter=array();
  
  public static function open() {
    self::$error[]=200;
    self::$mime[]="text/plain";
    ob_start();
  }
  
  public static function close() {
    return array("errorCode"=>array_pop(self::$error)->getErrorState(),"mime"=>array_pop(self::$mime),"output"=>ob_get_clean());
  }
  
  public static function publish($inst) {
    $r=self::$formatter[count(self::$formatter)-1]->handle($inst);
    if ($r==false) { throw "Fatal Error: Could not find an formatter"; }
  }

  public static function getErrorCode() {
    return self::$error[count(self::$error)-1]->getErrorState();
  }
  
  public static function setErrorCode($err) {
    self::$error[count(self::$error)-1]->setErrorState($err);
  }
  
  public static function getMime() {
    return self::$mime[count(self::$mime)-1];
  }
  
  public static function setMime($type) {
    self::$mime[count(self::$mime)-1]=$type;
  }
  
}

GlobalConsts::init();
?>