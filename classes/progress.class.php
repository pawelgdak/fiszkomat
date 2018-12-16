<?php

class Progress {

	public static function Get($level) {

		switch($level) {
			case '0':
				$p = 0;
				$color = 'warning';
				break;
			case '1':
				$p = 100;
				$color = 'success';
				break;
			case '2':
				$p = 92;
				$color = 'success';
				break;
			case '3':
				$p = 78;
				$color = 'almost';
				break;
			case '4':
				$p = 64;
				$color = 'almost';
				break;
			case '5':
				$p = 50;
				$color = 'warning';
				break;
			case '6':
				$p = 40;
				$color = 'warning';
				break;
			case '7':
				$p = 30;
				$color = 'danger';
				break;
			case '8':
				$p = 20;
				$color = 'danger';
				break;
			case '9':
				$p = 10;
				$color = 'danger';
				break;

		}

		return array('Percentage'=>$p, 'Color'=>$color);

	}

}