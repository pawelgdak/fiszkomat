<?php

class Clone_Action {

	public $error_text;
	
	public function Clone() {

		if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['clonecat'] == "true") {
		  
			if(!isset($_POST['cloneCode']) || empty($_POST['cloneCode']) || !isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['lang']) || empty($_POST['lang'])) {
				$this->error_text = 'Musisz wypełnić wszystkie pola!';
			} else $this -> CloneCat($_POST['cloneCode'], $_POST['name'], $_POST['lang']);
		
		}

		Render::Element('Title', array('title'=>"Sklonuj kategorię"));

		?>

		Jeśli posiadasz kod do sklonowania kategorii (np. pozyskany poprzez wygenerowanie z jednej ze swoich kategorii lub otrzymany od innego użytkownika), możesz go wkleić w pole poniżej. Wszystkie słówka zostaną skopiowane do nowej kategorii.

		<?php

		$this->Form();

	}

	private function Form() {

		$cookies = new Cookies();
		$lang = $cookies->getLang();

		?>

		<form method="POST" action="" class="mt-4">

			<input type="hidden" value="true" name="clonecat" />
			
			<label for="name">Nazwa nowej kategorii</label> 
			<input type="text" name="name" id="name" placeholder="Nazwa kategorii" class="form-control" />
			
			<label class="mt-3" for="lang">Język nauki (dla nowej kategorii)</label>
			<select class="form-control" id="lang" name="lang">
				<option value="en" <?php echo $lang == "en" ? "selected" : "" ?>>Angielski</option>
				<option value="es" <?php echo $lang == "es" ? "selected" : "" ?>>Hiszpański</option>
				<option value="de" <?php echo $lang == "de" ? "selected" : "" ?>>Niemiecki</option>
				<option value="fr" <?php echo $lang == "fr" ? "selected" : "" ?>>Francuski</option>
				<option value="it" <?php echo $lang == "it" ? "selected" : "" ?>>Włoski</option>
				<option value="ru" <?php echo $lang == "ru" ? "selected" : "" ?>>Rosyjski</option>
			</select>

			<label class="mt-3" for="cloneCode">Kod klonowania</label>
			<input type="text" name="cloneCode" id="cloneCode" placeholder="Kod klonowania" class="form-control" />

			<button class="btn btn-primary mt-3">Klonuj</button>

			<p><?php echo $this->error_text; ?></p>

		</form>

		<?php

	}

	private function CloneCat($code, $name, $lang) {

		$db = new DB();

		$cat = $db->select('id', 'categories', '`clone-code`=:code', array(":code"=>$code), 'one')['id'];

		if(is_numeric($cat)) {

			$db -> insert('categories', 'id, lang, user_id, name, description', 'NULL, :lang, :uid, :name, ""', array(":lang"=>$lang, ":uid"=>$_SESSION['user_id'], ":name"=>$name));
			$id = $db->lastId();

			$words = $db->select('first, second, description', 'words', 'cat_id = :cat_id', array(":cat_id"=>$cat));
			$binds = "";
			$values = array();
			$i = 0;

			foreach($words as $word) {

				if($i != 0) $binds .= ", ";
				$binds .= "(?, ?, ?, ?, ?, ?)";

				array_push($values, $id, $lang, $_SESSION['user_id'], $word['first'], $word['second'], $word['description']);

				$i++;
				
			}

			$db->insert('words', 'cat_id, lang, user_id, first, second, description', $binds, $values, true);

			History::Insert("Klonowanie kategorii", $_SESSION['user_id'], $id);
			Header::Home('?c=fiszki&a=edit&id=' . $id);

		}

	}

}