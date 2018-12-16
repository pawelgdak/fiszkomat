<?php

class Hash {
	
	public static function Generate() {

		return md5(sha1(rand()*12-41));

	}

	public static function Crypt($str) {

		$salt = Config::get('Site/Salt');
		return md5(sha1(sha1(sha1(md5('1337' . $str . $salt)))));

	}

	public static function Compare($str, $hash) {

		$salt = $salt = Config::get('Site/Salt');
		if($hash === md5(sha1(sha1(sha1(md5('1337' . $str . $salt)))))) return true; else return false;

	}

}