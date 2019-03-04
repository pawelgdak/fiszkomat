<?php

function dodajFiszkeInput() {
	include('../templates/elements/Dashboard/fiszki_add_list.element.php');

	if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['lang_long']) || empty($_POST['lang_long'])) {
		exit;
	}

	$fiszki = new Fiszki_Add_List_Element();
	if(isset($_POST['count'])) {
		for($i = $_POST['id']; $i < $_POST['count'] + 1; $i++) {
			$fiszki->Render(array("id"=>$i, "lang_long"=>$_POST['lang_long']));
		}
	} else $fiszki->Render(array("id"=>$_POST['id'], "lang_long"=>$_POST['lang_long']));

}

function usunFiszke() {

	if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['lang']) || empty($_POST['lang'])) {
		exit;
	}

	$db = new DB();
    $db->query('DELETE from words WHERE id = :id AND user_id = :uid', array(":id"=>$_POST['id'], ":uid"=>$_SESSION['user_id']));
    History::Insert("Usuniecie fiszki", $_SESSION['user_id'], $_POST['id']);

}

function autoTranslate() {

	if(!isset($_POST['setting']) || empty($_POST['setting'])) {
		exit;
	}

	$cookies = new Cookies();
	$cookies->update('autoTranslate', $_POST['setting']);
	$cookies->save();

}

function changeSettings() {

	if(!isset($_POST['type']) || empty($_POST['type']) || !isset($_POST['value']) || empty($_POST['value'])) exit;

	$cookies = new Cookies();
	$cookies->update('NaukaSettings/' . $_POST['type'], $_POST['value']);
	$cookies->save();

}

function zmienKat() {

	if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['cat']) || empty($_POST['cat'])) {
		echo '{"result":false}';
		exit;
	}

	$db = new DB();

	if(!$db->checkIfOwner('categories', $_POST['cat'], $_SESSION['user_id'])) {
		echo '{"result":false}';
		exit;
	}

	$db->update('words', 'cat_id=:cat', array(':cat'=>$_POST['cat'], ':id'=>$_POST['id']), 'id=:id');

	echo '{"result":true}';

}

//if(!isset($_SESSION['user_id'])) exit;

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	include('../init.php');
	if($_POST['method'] == 'dodajInput') dodajFiszkeInput();
	elseif($_POST['method'] == 'usunfiszke') usunFiszke();
	elseif($_POST['method'] == 'zmienKat') zmienKat();
	elseif($_POST['method'] == 'autoTranslate') autoTranslate();
	elseif($_POST['method'] == 'changeSettings') changeSettings();
} else exit;