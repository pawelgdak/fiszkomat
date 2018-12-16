<?php

require('../vendor/autoload.php');
use Stichoza\GoogleTranslate\TranslateClient;

function translate(){

	include('../init.php');

	if(!isset($_POST['lang']) || empty($_POST['lang']) || !isset($_POST['value']) || empty($_POST['value'])) {
		echo '{"error":"true"}';
		exit;
	}

	if(!in_array($_POST['lang'], Config::get('Languages'))) $lang = 'en';
	else $lang = $_POST['lang'];

	try {

		$tr = new TranslateClient('pl', $lang);

		$translation = $tr->translate($_POST['value']);

		echo '{"error":"false", "translation":"' . $translation . '"}';

	} catch(Exception $e) {

		echo '{"error":"true"}';

	}

}

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	translate();
else exit;