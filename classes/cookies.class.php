<?php

class Cookies {

	private $data;
	private $cookies_hash;

	public function __construct() {

		$this->cookies_hash = Config::get('Site/Cookie');

		if(isset($_COOKIE[$this->cookies_hash])) {
			$this->data = unserialize($_COOKIE[$this->cookies_hash]);
		} else {
			$this->data = array();
		}

	}

	public function printAll() {

		?>
		<pre>

		<?php

		print_r($this->data);

		?>

		</pre>

		<?php

	}

	public function get($path) {

		$path = explode('/', $path);
		$data = $this->data;
		$set = false;
						
		foreach($path as $p) {
			if(isset($data[$p])) {
				$data = $data[$p];
				$set = true;
			}
		}
		
		if($set) return $data;
		else return false;

	}

	public function getLang() {

		$languages = Config::get('Languages');

		if($lang = $this->get('Language')) {
			if(in_array($lang, $languages)) return $lang;
			else return 'en';
		} else return 'en';
	}

	public function update($path, $new) {

		$path = explode('/', $path);

		if(sizeof($path) == 1) {
			$this->data[$path[0]] = $new;
		} elseif(sizeof($path) == 2) {
			$this->data[$path[0]][$path[1]] = $new;
		}

	}

	public function delete($path) {

		$path = explode('/', $path);
		if(sizeof($path) == 1) {
			if(isset($this->data[$path[0]])) unset($this->data[$path[0]]);
		} elseif(sizeof($path) == 2) {
			if(isset($this->data[$path[0]][$path[1]])) unset($this->data[$path[0]][$path[1]]);
		}

	}

	public function save() {

		setcookie($this->cookies_hash, serialize($this->data), time() + (86400 * 30 * 12), "/");

	}

}