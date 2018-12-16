<?php

class Account_Controller extends Template {

	public function getTemplate() {
		return parent::getTemplate('Account');
	}

	public function Titles() {
		return array(
			'main'=>'Twoje konto',
			'signin'=>'Logowanie',
			'signup'=>'Rejestracja'
		);
	}

	public function Main() {
        Header::Home('?c=account&a=signin');
    }

	public function Logout() {

        History::Insert("Wylogowanie", $_SESSION['user_id']);

		unset($_SESSION['logged']);
		unset($_SESSION['user_id']);
		unset($_SESSION['user']);
		unset($_SESSION['user_type']);

		$cookies = new Cookies();
		$cookies->delete('remember');
        $cookies->save();

		Header::Home();


	}

}