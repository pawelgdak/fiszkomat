<?php

class OldF_Action {

	public function OldF() {

		$db = new DB();
		$cookies = new Cookies();
		$lang = $cookies->getLang();

		$rows = $db->select('id, first as pl, second as fo, description as note, lang', 'words', 'lang=:lang AND user_id=:uid AND last_answer is not NULL ORDER BY last_answer ASC LIMIT 20', array(":lang"=>$lang, ":uid"=>$_SESSION['user_id']));

		?>

		Jeśli nie wyskoczyło Ci okienko z nauką słówek, spróbuj odświeżyć stronę.

		<?php Nauka::Create(array('title'=>'Stare słówka', 'words'=>$rows, 'mode'=>'old')); ?>

		<?php

	}

}