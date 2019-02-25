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

		Zalogowany jako

		<?php

		echo $_SESSION['user'];


	}

}