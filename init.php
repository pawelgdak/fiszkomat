<?php

error_reporting(E_ALL ^ E_STRICT);

session_start();
$GLOBALS['config'] =
  array(
    'sql' => array(
      'host' => 'localhost',
      'dbname' => 'fiszkomat',
      'user' => 'root',
      'password' => ''
    ),
    'Directories' => array(
      'Main' => $_SERVER['DOCUMENT_ROOT'] . '/fiszkomat',
      'Controllers' => $_SERVER['DOCUMENT_ROOT'] . '/fiszkomat/controllers/',
      'Actions' => $_SERVER['DOCUMENT_ROOT'] . '/fiszkomat/actions/',
      'Templates' => $_SERVER['DOCUMENT_ROOT'] . '/fiszkomat/templates/',
      'Elements' => $_SERVER['DOCUMENT_ROOT'] . '/fiszkomat/templates/elements/',
    ),
    'Locations' => array(
      'Home' => 'http://' . 'localhost/fiszkomat',
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
      'domain' => 'http://' . 'localhost/fiszkomat',
      'path' => $_SERVER['REQUEST_URI'],
      'Title' => 'Fiszkomat',
      'Author' => 'pawelgdak.pl Paweł Gdak',
      'Description' => 'opis..',
      'maintance' => false,
      'Cookie' => 'cookies',
      'Salt' => 'salt'
    ));


include("functions/getfiles.php");

include($_SERVER['DOCUMENT_ROOT']."/fiszkomat/templates/templates.class.php");
$Classes = getFileList($_SERVER['DOCUMENT_ROOT']."/fiszkomat/classes");
foreach($Classes as $Class) {
  include($Class["name"]);
}
