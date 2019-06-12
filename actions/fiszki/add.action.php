<?php

class Add_Action {

	public function Add() {

		if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['addnewcat'] == "true") {
	      
	      $this -> AddNewCat($_POST['cat_name'], $_POST['cat_desc']);
	      
	    }

		Render::Element('Title', array('title'=>'Dodaj nowe fiszki'));

		$this->Form_Category();

	}

	private function Form_Category() {

		?>

		<form class="form-addnewcat" action="" method="POST">
	      
	      <input type="hidden" name="addnewcat" value="true">

	      <label for="cat_name" class="sr-only">Nazwa kategorii</label>
	      <input type="text" name="cat_name" id="cat_name" class="form-control" placeholder="Nazwa kategorii" required autofocus>

	      <br>

	      <label for="cat_desc" class="sr-only">Nazwa kategorii</label>
	      <textarea name="cat_desc" id="cat_desc" class="form-control" placeholder="Opis kategorii"></textarea>

	      <br><br>

	      <button class="btn btn-lg btn-primary btn-block" type="submit">Dalej</button>

	    </form>


		<?php

	}

	private function AddNewCat($name, $desc) {

		if(!isset($name) || empty($name)) {
			Alert::Fail('Musisz wypełnić wymagane pola!');
			Header::Back();
		}

		if(strlen($name) > 150) {
			Alert::Fail('Nazwa nie może przekraczać 150 znaków.');
			Header::Back();
		}

		if(empty($desc) || !isset($desc)) $desc = '';

		$cookies = new Cookies();
		$lang = $cookies->getLang();

		$db = new DB();
		$db -> insert('categories', 'id, lang, user_id, name, description', 'NULL, :lang, :uid, :name, :description', array(":lang"=>$lang, ":uid"=>$_SESSION['user_id'], ":name"=>$name, ":description"=>$desc));

        $id = $db->lastId();
        
        History::Insert("Nowa kategoria", $_SESSION['user_id'], $id);

		Header::Home('?c=fiszki&a=edit&id=' . $id);

	}

}