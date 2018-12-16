<?php

error_reporting(E_ALL ^ E_STRICT);

session_start();
$GLOBALS['config'] = 
  array(
    'sql' => array(
      'host' => '',
      'dbname' => '',
      'user' => '',
      'password' => ''
    ),
    'Directories' => array(
      'Main' => $_SERVER['DOCUMENT_ROOT'],
      'Controllers' => $_SERVER['DOCUMENT_ROOT'] . '/controllers/',
      'Actions' => $_SERVER['DOCUMENT_ROOT'] . '/actions/',
      'Templates' => $_SERVER['DOCUMENT_ROOT'] . '/templates/',
      'Elements' => $_SERVER['DOCUMENT_ROOT'] . '/templates/elements/',
    ),
    'Locations' => array(
      'Home' => 'http://' . '',
    ),
    'Languages' => array(
      'en',
      'es',
      'de',
      'fr',
      'ru',
      'it'
    ),
    'Site' => array(
      'domain' => 'http://' . '',
      'path' => $_SERVER['REQUEST_URI'],
      'Title' => 'Fiszkomat',
      'Author' => 'pawelgdak.pl Paweł Gdak',
      'Description' => 'opis..',
      'maintance' => false,
      'Cookie' => '',
      'Salt' => ''
    ));


include("functions/getfiles.php");

include($_SERVER['DOCUMENT_ROOT']."/templates/templates.class.php");
$Classes = getFileList($_SERVER['DOCUMENT_ROOT']."/classes");
foreach($Classes as $Class) {
  include($Class["name"]);
}
