<?php

class Account extends Template {

	public function __construct() {

		$css = array(Config::get("Site/domain") . '/assets/css/account.css');
		parent::insertCss($css);
		parent::setBodyClass('text-center');

	}
	
	public function Header() {

		Alert::Show();		

	}

	public function Footer() {



	}

}