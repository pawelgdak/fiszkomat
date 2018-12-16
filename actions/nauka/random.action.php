<?php

class Random_Action {

	public function Random() {

		$db = new DB();
		$cookies = new Cookies();
		$lang = $cookies->getLang();
    
    if(isset($_GET['count'])) {
      if(is_numeric($_GET['count'])) $count = $_GET['count'];
    } else $count = 20;

		$rows = $db->select('id, first as pl, second as fo, description as note, lang', 'words', 'lang=:lang AND user_id=:uid ORDER BY RAND() LIMIT ' . $count, array(":lang"=>$lang, ":uid"=>$_SESSION['user_id']));

		?>

		Jeśli nie wyskoczyło Ci okienko z nauką słówek, spróbuj odświeżyć stronę.

		<?php Nauka::Create(array('title'=>'Stare słówka', 'words'=>$rows, 'mode'=>'old')); ?>

		<?php

	}

}