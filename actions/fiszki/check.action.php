<?php

class Check_Action {
	
	public function Check() {

		$db = new DB();

		if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
			Header::Home('?c=fiszki');
		}

		if(!$db->checkIfOwner('categories', $_GET['id'], $_SESSION['user_id'])) {
			Header::Home();
		}

		$cat = $db->select('*', 'categories', 'id=:id', array(":id"=>$_GET['id']), 'one');
		$additional = '<a href="' . Config::getHome() . '/?c=fiszki&a=edit&id=' . $cat['id'] . '" class="btn btn-primary" role="button"><i class="fas fa-cog"></i> Edytuj</a>';

		Render::Element('Title', array('title'=>$cat['name'], 'add'=>$additional));

		$words = $db->select('*', 'words', 'cat_id = :cat_id AND level > 0 ORDER BY level DESC, good_in_a_row ASC', array(':cat_id' => $cat['id']));
		$wordsNew = $db->select('*', 'words', 'cat_id = :cat_id AND level = 0', array(':cat_id' => $cat['id']));

		?>

		<p class="md-1"><?php echo $cat['description']; ?></p>
		<ul class="list-group list-group-flush">

		<?php

		foreach($wordsNew as $word) {

			$this->ListElement($word);
			
		}

		foreach($words as $word) {

			$this->ListElement($word);

		}

		?>

		</ul>
		<br><br>
		<p class="small">
			Słówka sortowane są według poziomu znajomości. Lepiej znane słówka są na dole.
		</p>

		<?php

	}

	private function ListElement($word) {

		$progress = Progress::Get($word['level']);

		?>


		  <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center mobile-flex-column">
		  	<div style="flex-basis:60%;">
		  		<span><?php echo $word['first']; ?> <i class="fas fa-long-arrow-alt-right"></i> <?php echo $word['second']; ?></span>
		  		<i class="fas fa-volume-down ml-1 textToSpeech" data-text="<?php echo $word['second']; ?>" data-lang="<?php echo $word['lang']; ?>"></i>
		  	</div>
		  	<div class="progress progress-bar-mobile" style="flex-grow: 1;">
			  <div class="progress-bar bg-<?php echo $progress['Color']; ?>" style="width: <?php echo $progress['Percentage']; ?>%"></div>
			</div>
		  </li>
		

		<?php

	}

}