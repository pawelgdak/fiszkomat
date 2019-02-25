<?php

class Fiszki_Controller extends Template {

	public function getTemplate($temp = 'Def') {
		return parent::getTemplate('Dashboard');
	}

	public function insertCustomJs() {
		return $js = array(Config::get("Site/domain") . '/assets/js/fiszki.js');
	}

	public function Titles() {

		return array(
			'main' => "Moje fiszki",
			'add' => "Dodaj fiszki",
			'edit' => "Edytuj fiszki"
		);

	}

	public function Remove() {

		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			Header::Home();
		}

		$db = new DB();
		if(!$db->checkIfOwner('categories', $_GET['id'], $_SESSION['user_id'])) {
			Header::Home();
		}

		$lang = $db->select('lang', 'categories', 'id=:id', array(':id'=>$_GET['id']), 'one')['lang'];
		$db->query('DELETE from categories WHERE id = :id AND user_id = :uid', array(":id"=>$_GET['id'], ":uid"=>$_SESSION['user_id']));
		$db->usun('words', 'cat_id = :cat_id AND user_id = :uid', array(":cat_id"=>$_GET['id'], ":uid"=>$_SESSION['user_id']));

		Header::Home('?c=fiszki');

	}

}