<?php

class Good_Action {

	public function Good() {

		$db = new DB();
		$cookies = new Cookies();
		$lang = $cookies->getLang();

		if(isset($_GET['id'])) {
      
      $categories = explode(',', $_GET['id']);
      $rows = array();

      foreach($categories as $cat) {
        
        if(is_numeric($cat)) {
          
          if(!$db->checkIfOwner('categories', $cat, $_SESSION['user_id'])) {
            Header::Home();
          }
          
          $rows = array_merge($rows, $rows = $db->select('id, first as pl, second as fo, description as note, lang', 'words', 'cat_id=:cat AND (level = 1 OR level = 2 OR level = 3) AND lang=:lang AND user_id=:uid', array(":lang"=>$lang, ":uid"=>$_SESSION['user_id'], ":cat"=>$cat)));
        }
      }

		} else $rows = $db->select('id, first as pl, second as fo, description as note, lang', 'words', 'lang=:lang AND (level = 1 OR level = 2 OR level = 3) AND user_id=:uid LIMIT 20', array(":lang"=>$lang, ":uid"=>$_SESSION['user_id']));

		?>

		Jeśli nie wyskoczyło Ci okienko z nauką słówek, spróbuj odświeżyć stronę.

		<?php Nauka::Create(array('title'=>'Powtórz znane Ci słówka', 'words'=>$rows, 'mode'=>'good')); ?>

		<?php

	}

}