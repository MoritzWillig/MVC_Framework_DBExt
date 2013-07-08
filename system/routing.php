<?php
/**
 * Routing and controller management
 * 
 * @package    MVC_Framework
 * @subpackage System
 * @author     Max Weller <max.weller@teamwiki.net>, Moritz Willig <moritz.willig@gmail.com>
**/
  
  function split_url($url) {
    $p = strpos($url, "?");
    if ($p !== false) {
      parse_str(substr($url, $p + 1), $_GET);
      $url = substr($url, 0, $p);
    }
    if (substr($url,0,1)=="/") { $url=substr($url,1); }
    $q = explode("/", $url);
    array_splice($q,0,1); //***FIX LATER***
    if (count($q) == 0 || !$q[0]) $q = array("default", "index");
    elseif ((count($q) == 1) || $q[1]=="") $q = array($q[0], "index");

    return $q;
  }
  
  
  function load_controller() {
    $url = $_SERVER["REQUEST_URI"];
    if (substr($url, 0, strlen(URL_PREFIX)) == URL_PREFIX) { $url = substr($url, strlen(URL_PREFIX)); }
    if (substr($url, 0, strlen(URI_PREFIX)) == URI_PREFIX) { $url = substr($url, strlen(URI_PREFIX)); }
    
    $parts = split_url($url);
    
    $controller_class = preg_replace("/[^a-z0-9]/", "", $parts[0]);
    $controller_function = preg_replace("/[^a-z0-9_]/", "", $parts[1]);
    
    //Check for google webmaster verification
    $seo=GlobalConsts::get("SEO");
    if (($seo) and (isset($seo["GoogleVer"]))) {
      $gVer=$seo["GoogleVer"];
      if ($controller_class==preg_replace("/[^a-z0-9]/", "", $gVer)) {
        echo "google-site-verification: ".$gVer; die;
      }
    }
    
    $page=load_model("page"); $view="";
    if ($pages=$page->searchByPath("webpage",$url,$controller_class,$controller_function,$view)) {
      $currentPage=$pages[0];
      
      if (file_exists(CONTROLLER_DIR."/".$currentPage["controller"].".php")) {
        include(CONTROLLER_DIR."/".$currentPage["controller"].".php");
        $controller = ucfirst($currentPage["controller"])."Controller";
        $class = new $controller;
        
        $params=array();
        $currentPage[]=$currentPage;
        $currentPage[]=array_slice($parts, 2);
        if (method_exists($class, $currentPage["function"])) {
          call_user_func_array(array($class, $currentPage["function"]), $params);
        } else {
          if (method_exists($class, "_catchall")) {
            if (call_user_func_array(array($class, "_catchall"), $params)===false) {
              header("HTTP/1.0 404 Not Found");
              echo "404 Page not found";
            }
          } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Page not found";
          }
        }
      } else {
        header("HTTP/1.0 404 Not Found");
        echo "404 Page not found";
      }
      
    } else {
      header("HTTP/1.0 404 Not Found");
      echo "404 Page not found";
    }
  }
  
?>