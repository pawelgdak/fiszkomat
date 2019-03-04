<?php

class Edit_Action {

	public function Edit() {

		if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['edit_ticket'] == "true") {

	      $this->Process();

	    }

		$db = new DB();
		if(!$db->checkIfOwner('categories', $_GET['id'], $_SESSION['user_id'])) {
			Header::Home();
		}

		$cat = $db->select('*', 'categories', 'id=:id', array(":id"=>$_GET['id']), 'one');
		$words = $db->select('id, first, second, description', 'words', 'cat_id=:cat', array(':cat'=>$cat['id']));

		$categories_list = $db->select('*', 'categories', 'user_id=:uid AND lang=:lang', array(':uid'=>$_SESSION['user_id'], ':lang'=>$cat['lang']));

		$cookies = new Cookies();
		$at = $cookies->get('autoTranslate');

		if(!$at || $at == 'true') {

			$autotranslate = '<button id="at-button" data-setting="on" class="btn btn-secondary at-on">AutoTłumacz: <span class="at-text">WŁ<span></button>';


		} elseif(isset($at) && $at == 'false') {

			$autotranslate = '<button id="at-button" data-setting="off" class="btn btn-secondary at-off">AutoTłumacz: <span class="at-text">WYŁ</span></button>';

		}



		$additional = $autotranslate . '<a style="margin-left: 5px;" href="' . Config::getHome() . '/?c=fiszki&a=check&id=' . $cat['id'] . '" class="btn btn-primary" role="button"><i class="fas fa-list"></i> Zobacz</a> <a style="" id="removeCategory" href="' . Config::getHome() . '/?c=fiszki&a=remove&id=' . $cat['id'] . '" class="btn btn-danger" role="button"><i class="fas fa-trash"></i> Usuń</a>';

		Render::Element('Title', array('title'=>'<div class="edit-element flex" data-min-length="3" data-max-length="150" data-id="' . $cat['id'] . '" data-type="categories" data-element="name" data-input="text">Edycja: <span class="edit-element-child edit-cat-name" style="padding-left: 10px;">' . $cat['name'] . '</span></div>', 'add'=>$additional));

		switch($cat['lang']) {
			case 'en':
				$lang_long = 'angielski';
				break;
			case 'es':
				$lang_long = 'hiszpański';
				break;
			case 'de':
				$lang_long = 'niemiecki';
				break;
			case 'fr':
				$lang_long = 'francuski';
				break;
			case 'ru':
				$lang_long = 'rosyjski';
                break;
			case 'it':
                $lang_long = 'włoski';
                break;
		}

		$desc = !empty($cat['description']) ? $cat['description'] : 'Dodaj opis';

		?>

		<div class="edit-element" class="edit-element" data-empty-text="Dodaj opis" data-type="categories" data-element="description" data-input="textarea" data-id="<?php echo $cat['id']; ?>">
			<p class="md-1 edit-element-child"><?php echo $desc; ?></p>
		</div>

		<form action="" method="POST" id="fiszki">

			<input type="hidden" name="lang" value="<?php echo $cat['lang']; ?>">

			<input type="hidden" name="lang_long" value="<?php echo $lang_long; ?>">

			<input type="hidden" name="cat_id" value="<?php echo $cat['id']; ?>">

			<input type="hidden" name="edit_ticket" value="true">

			<?php

			$i=0;

			foreach($words as $word) {

				$i++;

				Render::Element('Fiszki_Add_List', array("id"=>$i, "lang_long"=>$lang_long, "edit"=>true, "pol"=>$word['first'], "for"=>$word['second'], "note"=>$word['description'], "word_id"=>$word['id']));

			}

			?>


			<?php Render::Element('Fiszki_Add_List', array("id"=>1, "lang_long"=>$lang_long)); ?>


		</form>

		<br><button class="btn btn-primary" id="saveFlashcards">Zapisz</button>

		<div id="changeCatModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
			    <div class="modal-content">
			    	<div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLongTitle">Zmień kategorię słówka</h5>
				      </div>
			      	<div class="modal-body">

			      		<select class="changeCatSelect" style="width: 100%">
			      			<option disabled selected value="def">Wybierz kategorię</option>
			      			<?php foreach($categories_list as $cat) { ?>
			      				<option value="<?php echo $cat['id'] ?>"><?php echo $cat['name']; ?></option>
		      				<?php } ?>
			      		</select>

			      	</div>

			      	<div class="modal-footer">
			      		<button class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
			      	</div>
			    </div>
			  </div>
			</div>

		</div>

		<?php

	}

	public function Process() {

		$db = new DB();

		if(!isset($_POST['pl']) || !isset($_POST['note']) || !isset($_POST['fo'])) {
			Header::Back();
		}

		if(!$db->checkIfOwner('categories', $_POST['cat_id'], $_SESSION['user_id'])) {
			Header::Home();
		}

		$lang = Config::fixLang($_POST['lang']);

		if(isset($_POST['pl'])) {

			$count = sizeof($_POST['pl']);
			$insertWords = array();
			$binds = '';

			for($i=0; $i<$count; $i++){

				if(isset($_POST['pl'][$i]) && !empty($_POST['pl'][$i]) &&
					isset($_POST['fo'][$i]) && !empty($_POST['fo'][$i])) {

					$binds .= '(NULL, :cat_id_' . $i . ', :lang_' . $i . ', :user_id_' . $i . ', :first_' . $i . ', :second_' . $i . ', :desc_' . $i . '), ';
					$insertWords = array_merge($insertWords, array(":cat_id_" . $i => $_POST['cat_id'], ':lang_' . $i => $_POST['lang'], ":user_id_" . $i => $_SESSION['user_id'], ":first_" . $i => $_POST['pl'][$i], ":second_" . $i => $_POST['fo'][$i], ":desc_" . $i => $_POST['note'][$i]));

				}

			}

			$binds = rtrim($binds, ', ');
			$db->insert('words', 'id, cat_id, lang, user_id, first, second, description', $binds, $insertWords, true);

		}

		if(isset($_POST['pledit'])) {

			$edit_count = sizeof($_POST['pledit']);
			$binds_edit = '';
			$insertWords_edit = array();

			for($i=0; $i<$edit_count; $i++){

				if(isset($_POST['pledit'][$i]) && !empty($_POST['pledit'][$i]) &&
					isset($_POST['foedit'][$i]) && !empty($_POST['foedit'][$i]) &&
					isset($_POST['wordid'][$i]) && !empty($_POST['wordid'][$i])) {

					$binds_edit .= '(:id_' . $i . ', :first_' . $i . ', :second_' . $i . ', :desc_' . $i . '), ';
					$insertWords_edit = array_merge($insertWords_edit, array(":id_" . $i => $_POST['wordid'][$i], ":first_" . $i => $_POST['pledit'][$i], ":second_" . $i => $_POST['foedit'][$i], ":desc_" . $i => $_POST['noteedit'][$i]));

				}

			}

			$binds_edit = rtrim($binds_edit, ', ');

			$db->query('INSERT INTO words (id, first, second, description) VALUES ' . $binds_edit . ' ON DUPLICATE KEY UPDATE id=VALUES(id), first=VALUES(first), second=VALUES(second), description=VALUES(description);', $insertWords_edit);

		}

        History::Insert("Edycja kategorii", $_SESSION['user_id'], $_POST['cat_id']);

        $curdate = date('Y-m-d H:i:s');
        $db->update('categories', 'edit_date=:date', array(':date'=>$curdate, ":id"=>$_POST['cat_id']), 'id=:id');
		Header::Back();
	}

}