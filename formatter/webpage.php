<?php

class WebpageFormatter implements FormatterTemplate {
  
  function format($instance,$data) {
    return file_get_contents($file);
  }
  
}

?>