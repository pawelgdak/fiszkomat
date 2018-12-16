<?php

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	include('../init.php');

	$db = new DB();
	if(!isset($_POST['id']) || empty($_POST['id'] || !isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) || 
		!isset($_POST['editType']) || empty($_POST['editType']) || !isset($_POST['elementType']) || empty($_POST['elementType']) ||
		!isset($_POST['newName'])) {
		echo '{"result":"fail"}';
		exit;
	}

	$types = array('categories');
	$elements = array('name', 'description');

	if(!in_array($_POST['editType'], $types)) {

		echo '{"result":"fail"}';
		exit;

	}

	if(!in_array($_POST['elementType'], $elements)) {

		echo '{"result":"fail"}';
		exit;

	}
	
	if(@$db->checkIfOwner('categories', $_POST['id'], $_SESSION['user_id'])) {

		$el = ":" . $_POST['elementType'];

		$db->update($_POST['editType'], $_POST['elementType'] . '=:' . $_POST['elementType'], array($el => trim($_POST['newName']), ":id"=>$_POST['id']), "id=:id");
		echo '{"result":"done"}';
		
	} else echo '{"result":"fail"}';


} else exit;