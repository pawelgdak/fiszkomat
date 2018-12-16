<?php

class Jezyk_Controller extends Template {

	public function getTemplate() {
		return parent::getTemplate('Dashboard');
	}

	public function Titles() {
		return array('main'=>'Zmień język nauki');
	}

	public function Main() {

		Render::Element('Title', array('title'=>'Język'));

		?>	

		<p>Wybierz język, którego chcesz się uczyć</p>

		<?php

		Render::Element('Language');

	}
	
}