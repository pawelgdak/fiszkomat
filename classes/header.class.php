<?php

class Header {
 
  public static function to($location) {
    
    header('Location: ' . $location);
    exit();
    
  }
  
  public static function Home($loc = '') {
    
    if(empty($loc)) {
      
      header('Location: ' . Config::get('Locations/Home'));
      exit();

    } else {
      
      header('Location: ' . Config::get('Locations/Home') . $loc);
      exit();
      
    }
    
  }
  
  public static function Back() {
   
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header('Location: ' . $actual_link);
    exit();
    
  }
  
}
