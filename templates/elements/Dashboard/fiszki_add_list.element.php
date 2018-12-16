<?php

class Fiszki_Add_List_Element {

	public function Render($arr = false) {

		if(isset($arr['id'])) $id = $arr['id'];
		else $id = 0;

		if(isset($arr['lang_long'])) $lang_long = $arr['lang_long'];
		else $lang_long = 'angielski';

		if(isset($arr['edit']) && $arr['edit'] == true) {

			?>

			<li class="list-group-item list-group-item-action flex-column align-items-start" data-id="<?php echo $id; ?>">
				
				<div class="w-100 jedna-fiszka">

					<div class="fiszki-first" style="width: 47.5%">
						<input type="hidden" name="wordid[]" value="<?php echo $arr['word_id']; ?>">
						<input name="pledit[]" data-edit-id="<?php echo $id; ?>" class="form-control input-pl-edit" type="text" placeholder="polski" value="<?php echo $arr['pol']; ?>"></input>
						<small data-edit-id="<?php echo $id; ?>" style="cursor:pointer;" class="edit-note">Edytuj notatkę</small>
						<small>&#8226;</small>
						<small data-obj-id="<?php echo $id; ?>" data-edit-id="<?php echo $arr['word_id']; ?>" style="cursor:pointer;" class="remove-flashcard">Usuń fiszkę</small>
						<textarea name="noteedit[]" placeholder="Notatki" style="display:none" data-edit-id="<?php echo $id; ?>" class="form-control additional-notes"><?php echo $arr['note']; ?></textarea>
						<small>&#8226;</small>
						<small data-change-id="<?php echo $arr['word_id']; ?>" style="cursor:pointer;" class="change-cat">Zmień kategorię</small>
					</div>
					<div class="md-1 fiszki-przerwa" style="width: 15%; text-align: center;"><i class="fas fa-long-arrow-alt-right"></i></div>
					<div class="fiszki-second" style="width: 47.5%">
						<input name="foedit[]" data-edit-id="<?php echo $id; ?>" class="form-control input-foreign-edit" type="text" placeholder="<?php echo $lang_long; ?>" value="<?php echo $arr['for']; ?>"></input>

					</div>

				</div>

			</li>


			<?php

		} else {

			?>

			<li class="list-group-item list-group-item-action flex-column align-items-start">

				<div class="w-100 jedna-fiszka">

					<div class="fiszki-first" style="width: 47.5%">
						<input name="pl[]" data-id="<?php echo $id; ?>" class="form-control input-pl" type="text" placeholder="polski"></input>
						<small data-id="<?php echo $id; ?>" style="cursor:pointer;" class="add-note">Dodaj notatkę</small>
						<textarea name="note[]" placeholder="Notatki" style="display:none" data-id="<?php echo $id; ?>" class="form-control additional-notes"></textarea>
					</div>
					<div class="md-1 fiszki-przerwa" style="width: 15%; text-align: center;"><i class="fas fa-long-arrow-alt-right"></i></div>
					<div class="fiszki-second" style="width: 47.5%">
						<input name="fo[]" data-id="<?php echo $id; ?>" class="form-control input-foreign" type="text" placeholder="<?php echo $lang_long; ?>"></input>

					</div>

				</div>

			</li>

			<?php

		}

	}

}