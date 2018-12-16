<?php

class Dashboard extends Template {

	public $elements = array('menu', 'language', 'title', 'fiszki_add_list', 'profile');

	public function __construct() {

		$css = array(Config::get("Site/domain") . '/assets/css/dashboard.css', Config::get("Site/domain") . '/assets/css/dashboard-mobile.css', 'https://fonts.googleapis.com/css?family=Vollkorn+SC', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css');
		$js = array(Config::get("Site/domain") . '/assets/js/script.js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', 'https://code.responsivevoice.org/responsivevoice.js');
		parent::insertCss($css);
		parent::insertJs($js);

	}

	public function Header() {

		if(!isset($_SESSION['logged'])) Header::Home();
		Alert::Show();
		$cookies = new Cookies();
		$lang = $cookies->getLang();

		// Render::Element('language');

		?>

		<nav class="navbar navbar-dark sticky-top bg-main flex-md-nowrap p-0">
	      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="<?php echo Config::getHome(); ?>">Fiszkomat</a>
	      <ul class="profile-things"><?php Render::Element('profile'); ?></ul>
	    <div class="burger-menu-icon-container">
	    	<a id="toggle" class="burger-menu-icon" href="#"><i class="fas fa-bars"></i></a>
		</div>
	    </nav>

	    <div class="container-fluid">
	      <div class="row">

	      	<?php Render::Element('menu'); ?>

	        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 pb-3">

		<?php

	}

	public function Footer() {

		?>

			</main>
	      </div>
	    </div>

	    <!-- Icons -->
	    <script>
	    	
	      $('#toggle').click(function() {
		   $(this).toggleClass('burger-active');
		   $('#overlay').toggleClass('fadeOut fadeIn open');
		   $('.mobile-nav .nav-item').toggleClass('animated slideInRight');
		  });
		  $('.mobile-nav ul.nav').click(function(){
		  	$('#overlay').toggleClass('fadeIn fadeOut open');
		  	$('#toggle').toggleClass('burger-active');
		   	$('.mobile-nav .nav-item').toggleClass('animated slideInRight');
		  })

	    </script>
		
		<?php


	}

}