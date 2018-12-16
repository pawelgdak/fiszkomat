<?php

function getConf() {
	include('../init.php');

	echo Config::getHome();
}

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	getConf();
else exit;