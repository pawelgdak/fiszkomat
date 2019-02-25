<?php
ob_start();
//phpinfo();
require("init.php");

if(!isset($_SESSION['logged'])) {
	$cookies = new Cookies();
	if(!empty($hash = $cookies->get('remember/user-hash'))) {

		if(is_string($hash)) {

			$sth = DB::Connect() -> prepare('SELECT * FROM users WHERE cookie_hash = :cookie_hash');
			$sth -> execute(array(':cookie_hash' => $hash));
			$row = $sth->fetch(PDO::FETCH_ASSOC);

			if(isset($row) && is_numeric($row['id'])) {

				MyAccount::LoggedIn($row['email'], $row['id'], false, true);

			}

		}

	}

}

$error = new ErrorClass();

if(Config::get('Site/maintance')) {
	if(@$_SESSION['user_type'] != 5) {
		$error->setCode('100');
		$error->check();
		exit;
	}
}

if(isset($_SESSION['logged'])) {

	isset($_GET['c']) ? $page = $_GET['c'] : $page = 'indexLI';
	Nauka::checkIfStudied();

} else isset($_GET['c']) ? $page = $_GET['c'] : $page = 'account';


Fiszkomat::Index($page);
ob_end_flush();