<?php

class Main_Action {	

	public function Main() {

		$additional = '<a href="' . Config::getHome() . '/?c=fiszki&a=add" class="btn btn-primary" role="button"><i class="fas fa-plus"></i> Dodaj nowe</a>';

		Render::Element('Title', array('title'=>'Moje fiszki', 'add'=>$additional));

		?>

		<div class="list-group fiszki-kategorie">

		<?php

		$this->Listing();

		?>

		</div>

		<?php

	}

	private function Listing() {

		$cookies = new Cookies();
		$lang = $cookies->getLang();
		$db = new DB();
		$rows = $db->select('id, name, description', 'categories', 'user_id=:uid AND lang=:lang ORDER BY edit_date DESC', array(":uid"=>$_SESSION['user_id'], ":lang"=>$lang));


		foreach($rows as $row) {

			$umiem = $db->policz('words', 'cat_id = :cat_id AND (level = 1 OR level = 2 OR level = 3)', array(":cat_id"=>$row['id']));
			$utrwal = $db->policz('words', 'cat_id = :cat_id AND (level = 4 OR level = 5)', array(":cat_id"=>$row['id']));
			$naucz =  $db->policz('words', 'cat_id = :cat_id AND level > 5', array(":cat_id"=>$row['id']));
			$nowe = $db->policz('words', 'cat_id = :cat_id AND level = 0', array(":cat_id"=>$row['id']));

			?>

			<li class="list-group-item list-group-item-action flex-column align-items-start fiszki-cat">

				<div class="d-flex w-100 justify-content-between">

					<a class="category-name" cat-id="<?php echo $row['id']; ?>" href="<?php echo Config::getHome(); ?>/?c=fiszki&a=check&id=<?php echo $row['id']; ?>"><h4 class="mb-1"><?php echo $row['name']; ?></h4></a>

					<small><a class="category-edit-link" href="<?php echo Config::getHome(); ?>/?c=fiszki&a=edit&id=<?php echo $row['id']; ?>"><i class="fas fa-cog"></i> Edytuj</a>
						&#8226;
					<a style="color:black;" class="removeCategory" href="<?php echo Config::getHome() . '/?c=fiszki&a=remove&id=' . $row['id']; ?>" role="button"><i class="fas fa-trash"></i> Usuń</a></small>
					

				</div>

				<p class="mb-1">
					
					<a href="<?php echo Config::getHome() . '/?c=nauka&a=all&id=' . $row['id']; ?>"><span class="badge badge-primary badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Nauka wszystkich dostępnych fiszek z tej kategorii.">Wszystkie: <strong><?php echo $db->policz('words', 'cat_id = :cat_id', array(":cat_id"=>$row['id'])); ?></strong></span></a>

					<?php if($umiem) { ?>

					<a href="<?php echo Config::getHome() . '/?c=nauka&a=good&id=' . $row['id']; ?>"><span class="badge badge-success badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Powtórz sobie te fiszki, które już dobrze znasz.">Umiem: <strong><?php echo $umiem; ?></strong></span></a>

					<?php } else { ?>

					<span class="badge badge-success badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Brak fiszek znanych w bardzo dobrym stopniu.">Umiem: <strong><?php echo $umiem; ?></strong></span>

					<?php } ?>

					<?php if($utrwal) { ?>

					<a href="<?php echo Config::getHome() . '/?c=nauka&a=solid&id=' . $row['id']; ?>"><span class="badge badge-secondary badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Utrwal sobie te fiszki, które sprawiają Ci kłopot.">Utrwal: <strong><?php echo $utrwal; ?></strong></span></a>

					<?php } else { ?>

					<span class="badge badge-secondary badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Braw słówek do utrwalenia!">Utrwal: <strong><?php echo $utrwal; ?></strong></span>

					<?php } ?>

					<?php if($naucz) { ?>

					<a href="<?php echo Config::getHome() . '/?c=nauka&a=hardest&id=' . $row['id']; ?>"><span class="badge badge-danger badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Naucz się tych słówek, z którymi masz największy problem.">Naucz się: <strong><?php echo $naucz; ?></strong></span></a>

					<?php } else { ?>

					<span class="badge badge-danger badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Nie masz słówek, które sprawiają Ci duży problem!">Naucz się: <strong><?php echo $naucz; ?></strong></span>					

					<?php } ?>

					<?php if($nowe) { ?>
						<a href="<?php echo Config::getHome() . '/?c=nauka&a=newf&id=' . $row['id']; ?>"><span class="badge badge-info badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Naucz się fiszek, których jeszcze się nie uczyłeś.">Nowe: <strong><?php echo $nowe; ?></strong></span></a>
					<?php } else { ?>
						<span class="badge badge-info badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Brak nowych fiszek do nauki!">Nowe: <strong><?php echo $nowe; ?></strong></span>
					<?php } ?>

				</p>

				<p class="mb-1"><?php echo $row['description'] ?></p>

			</li>

			<?php

		}
    
    ?>

    

      <li id="study-selected" class="list-group-item list-group-item-action flex-column align-items-start disabled">

				<div class="d-flex w-100 justify-content-between">

					<h3>
            Wybrane kategorie
          </h3>

				</div>

				<p class="mb-1">
					
					<a class="study-selected-button" href="<?php echo Config::getHome() . '/?c=nauka&a=all'; ?>"><span class="disabled badge badge-primary badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Nauka wszystkich dostępnych fiszek z tej kategorii.">Wszystkie</span></a>

					<a class="study-selected-button" href="<?php echo Config::getHome() . '/?c=nauka&a=good'; ?>"><span class="disabled badge badge-success badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Powtórz sobie te fiszki, które już dobrze znasz.">Umiem</span></a>

					<a class="study-selected-button" href="<?php echo Config::getHome() . '/?c=nauka&a=solid'; ?>"><span class="disabled badge badge-secondary badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Utrwal sobie te fiszki, które sprawiają Ci kłopot.">Utrwal</span></a>

					<a class="study-selected-button" href="<?php echo Config::getHome() . '/?c=nauka&a=hardest'; ?>"><span class="disabled badge badge-danger badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Naucz się tych słówek, z którymi masz największy problem.">Naucz się</span></a>

					<a class="study-selected-button" href="<?php echo Config::getHome() . '/?c=nauka&a=newf'; ?>"><span class="disabled badge badge-info badge p-2 my-1" data-toggle="tooltip" data-placement="top" title="Naucz się fiszek, których jeszcze się nie uczyłeś.">Nowe</span></a>
					
				</p>

			</li>


    <?php

	}

}