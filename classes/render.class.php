<?php

class Render {
	
	public static function Element($name, $arr = false) {

		$class = $name . '_Element';
		$c = new $class;
		if($arr) $c->Render($arr);
		else $c->Render();

	}

}