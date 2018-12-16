<?php

class IndexLO_Controller extends Template {

	public function Titles() {
		return array(
			'Main' => 'Strona główna',
		);
	}

	public function Main() {

		?>
		
		<main role="main" class="inner cover">
			<h1 class="cover-heading">Chcesz nauczyć się języka?</h1>
			<p class="lead"><br>
			  <a href="?c=account&a=signup" class="btn btn-lg btn-secondary">Załóż konto</a>
			</p>
		</main>

		<?php

	}

}