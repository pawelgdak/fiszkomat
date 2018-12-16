<?php

class Alert {
  
  protected $Message;
  
  public static function Fail($msg, $delay = 5) {
    
    $delay *= 1000;
    
    if(!isset($_SESSION)) session_start();
    $_SESSION['alert'] = '
    
    <script>
    
    $.notify({
      // options
      message: "'.$msg.'" 
    },{
      // settings
      type: "danger",
      animate: {
        enter: "animated fadeInDown",
        exit: "animated fadeOutUp",
      },
      placement: {
        from: "top",
        align: "center"
      },
      mouseOver: "pause",
      delay: '.$delay.'
    });
    
    </script>
    
    
    ';
    
  }
  
  public static function Pass($msg, $delay = 5) {
    
    $delay *= 1000;
    
    if(!isset($_SESSION)) session_start();
    $_SESSION['alert'] = '
    
    <script>
    
    $.notify({
      // options
      message: "'.$msg.'" 
    },{
      // settings
      type: "success",
      animate: {
        enter: "animated fadeInDown",
        exit: "animated fadeOutUp",
      },
      placement: {
        from: "top",
        align: "center"
      },
      mouseOver: "pause",
      delay: '.$delay.'
    });
    
    </script>
    
    
    ';
    
  }
  
  public static function Show() {
    
    if(!isset($_SESSION)) session_start();
    if(isset($_SESSION['alert'])) {
      echo $_SESSION['alert'];
      unset($_SESSION['alert']);
    }
    
    
  }
  
}