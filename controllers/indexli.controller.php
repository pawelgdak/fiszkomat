<?php

class IndexLI_Controller extends Template {

	public function getTemplate($temp = 'Def') {
		return parent::getTemplate('Dashboard');
	}

	public function Titles() {
		return array(
			'main' => 'Strona główna',
		);
	}

	public function Main() {

        //$cookies = new Cookies();
        //$cookies->update('aaa/ss', "xD");
        //$cookies->save();
        //print_r($cookies->printAll());

		?>

		<h2>Strona główna</h2>

		<p>Witaj <?php echo $_SESSION['user']; ?>! 
		<?php echo $_SESSION['last_study'] ? 'Twoja ostatnia nauka miała miejsce ' . $_SESSION['last_study'] : ''; ?></p>

		<div class="row mt-4">
			<div class="col">
				<a href="?c=jezyk" class="btn btn-primary btn-sm">Zmień język nauki</a>
			</div>
		</div>

		<?php


	}

}