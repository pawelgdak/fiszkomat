<?php

class Config {
	
	public static function get($path = Null) {
		
		if(isset($GLOBALS['config'])) {
			$config = $GLOBALS['config'];
			$path = explode('/', $path);
						
			foreach($path as $p) {
				if(isset($config[$p])) {
					$config = $config[$p];
				}
			}
			
			return $config;
			
		} else {
			return 'Nie załadowano configu.';
		}
			
	}
	
	public static function getTitle() {
		
		isset($_GET['p']) ? $page = $_GET['p'] : $page = 'default';
		
		if(!is_array(self::get('custom_titles/' . $page))) {
			return mb_strtoupper(self::get('def/title') . ' - ' . self::get('custom_titles/' . $page));
		} else {
			return mb_strtoupper(self::get('def/title') . ' - ' . $page);
		}
		
		//return strtoupper(self::get('def/title'));
		
	}

	public static function getHome() {
		return self::get('Locations/Home');
	}
	
	public static function dateToText($date) {
	
		if(!isset($date)) return false;
		else {
			
			$date = date('Y-m-d', strtotime($date));

			echo date('Y-m-d');
			
			if($date == date('Y-m-d'))
				return 'dzisiaj';
			elseif($date == date('Y-m-d', time()-60*60*24))
				return 'wczoraj';
			elseif($date == date('Y-m-d', time()-60*60*24*2))
				return 'dwa dni temu';
			elseif($date < date('Y-m-d', time()-60*60*24*2) && $date > date('Y-m-d', time()-60*60*24*7))
				return 'kilka dni temu';
			elseif($date < date('Y-m-d', time()-60*60*24*6) && $date > date('Y-m-d', time()-60*60*24*15))
				return 'tydzień temu';
			elseif($date < date('Y-m-d', time()-60*60*24*14) && $date > date('Y-m-d', time()-60*60*24*22))
				return 'dwa tygodnie temu';
			else return $date;
		}
		
	}

	public static function getMinutes($minut)
	{
		// j.pol
		switch($minut)
		{
			case 0: return 0; break;
			case 1: return 1; break;
			case ($minut >= 2 && $minut <= 4):
			case ($minut >= 22 && $minut <= 24):
			case ($minut >= 32 && $minut <= 34):
			case ($minut >= 42 && $minut <= 44):
			case ($minut >= 52 && $minut <= 54): return "$minut minuty temu"; break;
			default: return "$minut minut temu"; break;
		}
		return -1;
	}
	 
	public static function getDiff($timestamp)
	{
		$now = time();

		if(!$timestamp) return 'nigdy';
	 
		if ($timestamp > $now) {
			echo 'Podana data nie może być większa od obecnej.'; // tutaj była 'zła data'
			return;
		}
	 
		$diff = $now - $timestamp;
	 
		$minut = floor($diff/60);
		$godzin = floor($minut/60);
		$dni = floor($godzin/24);	
	 
		if ($minut <= 60) {
			$res = self::getMinutes($minut);
			switch($res) 
			{
				case 0: return "przed chwilą";
				case 1: return "minutę temu";
				default: return $res;
			}	
		}
	 
		if ($godzin > 6 && $godzin < 48) {
			return $godzin . ' godzin temu';
		}

		elseif ($godzin > 0 && $godzin < 24) {
			$restMinutes = ($minut-(60*$godzin));
			$res = self::getMinutes($restMinutes);
			if ($godzin == 1) {
				return "Godzinę i ".$res;
			} else {
				return "$godzin godzin i ".$res;
			}			
		}
	 
		if ($godzin >= 24 && $godzin <= 48) {
			return "Wczoraj";
		}
	 
		switch($dni)
		{
			case ($dni < 7): return "$dni dni temu"; break;
			case 7: return "Tydzień temu"; break;
			case ($dni > 7 && $dni < 14): return "Ponad tydzień temu"; break;
			case 14: return "Dwa tygodznie temu"; break;
			case ($dni > 14 && $dni < 30): return "Ponad 2 tygodnie temu"; break;
			case 30: case 31: return "Miesiąc temu"; break;	
			case ($dni > 31): return date("Y-m-d", $timestamp); break;	
		}
		return date("Y-m-d", $timestamp);		  
	}



	public static function fixLang($lang) {

		if(in_array($lang, self::get('Languages'))) {
			return $lang;
		} else return 'en';

	}
	
}