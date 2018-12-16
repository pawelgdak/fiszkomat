<?php

function setLanguage($d) {

	include('../init.php');

	$data = json_decode(stripslashes($d['data']), true);
	$cookies = new Cookies();
	$cookies->update('Language', $data['id']);
    $cookies->save();
	
}

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')setLanguage($_POST);
else exit;