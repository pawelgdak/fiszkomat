<?php

class MyAccount {
	
	public static function LoggedIn($email, $user_id, $remember, $cookies_login = false) {
	    
		$sth = DB::Connect() -> prepare('SELECT * FROM users WHERE id = :id');
		$sth -> execute(array(':id' => $user_id));
		$row = $sth->fetch(PDO::FETCH_ASSOC);

		if($cookies_login) $text = 'Cookies'; else $text = "";
		History::Insert("Logowanie", $user_id, $text);

		if(empty($row['cookie_hash'])) {
		 
			$cookie_hash = 'FM-'.md5(rand());
			$sth = DB::Connect() -> prepare('UPDATE users SET cookie_hash = :cookie WHERE id = :id');
			$sth -> execute(array(":cookie" => $cookie_hash, ":id" => $user_id));
		  
		} else {
		 
		  	$cookie_hash = $row['cookie_hash'];
		  
		}

		if($remember) {

			$cookies = new Cookies();
			$cookies->update('remember/user-hash', $cookie_hash);
			$cookies->save();

		}

		$_SESSION['logged'] = True;
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user'] = $email;
		$_SESSION['user_type'] = $row['user_type'];
		Header::Home();

	}

	public static function GetUserData() {
		$db = new DB();

		$user_id = $_SESSION['user_id'];
		$last_study = $db->select("last_time", "study", "user_id = :user_id ORDER BY last_time DESC LIMIT 0,1", array(":user_id" => $user_id), 'one')['last_time'];

		if($last_study)
			$_SESSION['last_study'] = $last_study;
		else $_SESSION['last_study'] = false;
	}
 
}