<?php

class Def extends Template {

	public function __construct() {

		$css = array(Config::get("Site/domain") . '/assets/css/main.css');
		parent::insertCss($css);
		parent::setBodyClass('text-center');


	}

	public function Header() {


		Alert::Show();
		isset($_GET['c']) ? $page = $_GET['c'] : $page = 'def';
		/* Menu, Navigation */
		?>
		<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
			<header class="masthead mb-auto">
				<div class="inner">
				  <h3 class="masthead-brand">Fiszkomat</h3>
				  <nav class="nav nav-masthead justify-content-center">
				    <a class="nav-link <?php echo ($page == 'def' ? 'active' : ''); ?>" href="<?php echo Config::get('Site/domain'); ?>">Strona główna</a>
				    <a class="nav-link <?php echo ($page == 'features' ? 'active' : ''); ?>" href="?c=features">Korzyści</a>
				    <a class="nav-link <?php echo ($page == 'contact' ? 'active' : ''); ?>" href="?c=contact">Kontakt</a>
				    <a class="nav-link <?php echo ($page == 'user' ? 'active' : ''); ?>" href="?c=account&a=signin">Logowanie</a>
				  </nav>
				</div>
			</header>

		<?php

	}

	public function Footer() {

		?>

			<footer class="mastfoot mt-auto">
				<div class="inner">
				  <p>2018 &copy <a href="#">PwlDev.pl</a></p>
				</div>
			</footer>
		</div>

		<?php

	}

}