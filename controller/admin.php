<?php

class AdminController extends Controller {
  
  function __construct() {
    //$this->DB = load_model("database");
    $this->Session = load_model("session");
    if (!$this->Session->isAdmin()) {
      echo "This pages are secured for the site administrator.<br><br><a href='".URL_PREFIX."'>Go to the main page</a>";
      die;
    }
  }
  
  function index() {
    echo "<h1>Admin area</h1>";
  }
  
}

?>