<?php

class Nauka_Controller extends Template {

	public function insertCustomJS() {

		return array(Config::getHome() . '/assets/js/nauka.js');

	}

	public function getTemplate() {
		return parent::getTemplate('Dashboard');
	}

	public function Titles() {

		return array(
			'main'=>'Nauka',
		);

	}

}