<?php

class History {
 
  public static function Insert($action, $id = false, $info = '') {
    
    if($id){
      $user_id = $id;
    } else {
      if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
      } else {
        $user_id = 0;
      }
    }
      
    $ip = Ip::Get();
    
    $sth = DB::Connect() -> prepare('INSERT INTO history (user_id, action, date, ip, info) VALUES (:id, :action, :date, :ip, :info)');
    $sth -> execute(array(':id' => $user_id, ':action' => $action,':date' => date('Y-m-d H:i:s'), ':ip' => $ip, ":info" => $info));
    
  }
  
  public static function Online() {
    
    $sth = DB::Connect() -> prepare('UPDATE users SET last_activity = NULL WHERE id = :uid');
    $sth -> execute(array(':uid' => $_SESSION['user_id']));
    
  }
  
}