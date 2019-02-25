<?php

class Template {

	protected $cssArr = array();
	protected $jsArr = array();
	protected $bodyClass = '';
	private $title = false;
	private $ajax = array();

	public function __construct() {

	}

	public function Ajax($arr) {
		$this->ajax = $arr;
	}

	public function checkIfAjax($str) {

		if(in_array($str, $this->ajax)) return true;
		else return false;
	}

	public function getTemplate($temp = 'Def') {

		//if(file_exists($temp . '.template.php')) {
			include($temp . '.template.php');
			$t = new $temp;

			$this->checkElements($temp, $t);

			return $t;
		//} else return false;

	}

	private function checkElements($temp, $t) {

		if(isset($t->elements)) {

			$dir = Config::get('Directories/Elements') . $temp;
			foreach($t->elements as $element) {

				if(file_exists($dir . '/' . strtolower($element) . '.element.php')) {

					include($dir . '/' . strtolower($element) . '.element.php');

				}

			}
		}

	}

	public function insertCustomCss() {
		return false;
	}

	public function insertCss($links) {

		foreach($links as $link) {
			array_push($this->cssArr, $link);
		}

	}

	public function setTitle($obj, $action) {
		if(method_exists($obj, 'Titles')) {
			$arr = $obj->Titles();
			if(isset($arr[strtolower($action)])) $this->title = $arr[strtolower($action)];
		}
	}

	public function insertCustomJs() {
		return false;
	}

	public function insertJs($links) {

		foreach($links as $link) {
			array_push($this->jsArr, $link);
		}

	}

	public function setBodyClass($class) {
		$this->bodyClass = $class;
	}

	public function HtmlBegin() {

		?>

		<!DOCTYPE html>

		<?php echo $this->CreatedBy_Html(); ?>

		<html>
		<head>
			<title><?php echo Config::get('Site/Title'); if($this->title) echo ' - ' . $this->title; ?></title>

			<!-- Include files -->
			<!-- Bootstrap -->
			<link rel="stylesheet" href="<?php echo Config::getHome(); ?>/assets/css/bootstrap.min.css">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
			<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

			<!-- Style -->
			<link rel="stylesheet" href="<?php echo Config::getHome(); ?>/assets/css/styles.css">

			<!-- Animate -->
			<link rel="stylesheet" href="<?php echo Config::getHome(); ?>/assets/css/animate.css">

			<!-- Custom CSS -->
			<?php
			foreach($this -> cssArr as $css) {

				?><link rel="stylesheet" href="<?php echo $css ?>">
				<?php

			}
			?>

			<!-- Notify -->
			<script src="<?php echo Config::getHome(); ?>/assets/js/bootstrap-notify.min.js"></script>

			<!-- FavIcon -->
			<link rel='shortcut icon' type='image/x-icon' href="<?php echo Config::getHome(); ?>/favicon.ico" />

			<!-- Font Awesome -->
			<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script>
			<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script>

			<!-- Meta tags -->
			<meta charset="utf-8">
    		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
			<meta name="viewport" content="width=device-width, user-scalable=no" />

			<!-- PWA -->
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<link rel="apple-touch-icon" href="/favicon.ico">
			<link href="/favicon.ico" rel="apple-touch-startup-image" />
			<meta name="apple-mobile-web-app-status-bar-style" content="default">
			<meta name="apple-mobile-web-app-title" content="Fiszkomat">
			<script type="text/javascript">
			// Mobile Safari in standalone mode
			if(("standalone" in window.navigator) && window.navigator.standalone){

			// If you want to prevent remote links in standalone web apps opening Mobile Safari, change 'remotes' to true
			var noddy, remotes = false;

			document.addEventListener('click', function(event) {

				noddy = event.target;

				// Bubble up until we hit link or top HTML element. Warning: BODY element is not compulsory so better to stop on HTML
				while(noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
					noddy = noddy.parentNode;
				}

				if('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes))
				{
					event.preventDefault();
					document.location.href = noddy.href;
				}

			},false);
			}
			</script>

		</head>
		<body class="<?php echo $this->bodyClass; ?>">

		<?php

	}

	public function HtmlEnd() {

		foreach($this -> jsArr as $js) {

			?><script src="<?php echo $js ?>"></script>
			<?php

		}

		?>

		</body>
		</html>

		<?php

	}

	public function CreatedBy_Html() {

		return '

		<!-- Created by pawelgdak.pl -->
		';

	}
}