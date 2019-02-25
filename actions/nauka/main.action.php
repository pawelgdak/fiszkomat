<?php

class Main_Action {

	public function Main() {

		$db = new DB();
		$cookies = new Cookies();
		$lang = $cookies->getLang();

		$new = $db->policz('words', 'lang=:lang AND user_id=:uid AND last_answer is NULL AND level = 0', array(":lang"=>$lang, ":uid"=>$_SESSION['user_id']));
		$all = $db->policz('words', 'lang=:lang AND user_id=:uid', array(':lang'=>$lang, ':uid'=>$_SESSION['user_id']));
		$lastStudy = $db->select('last_time', 'study', 'user_id=:uid AND study_type="std" AND lang=:lang', array(':lang'=>$lang, ':uid'=>$_SESSION['user_id']), 'one');

		?>

		<div class="list-group">

		  <a href="<?php echo Config::getHome() ?>/?c=nauka&a=standard" class="list-group-item list-group-item-action flex-column align-items-start">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Nauka standardowa</h5>
		    </div>
		    <p class="mb-1">Słówka do nauki polecane przez nasz system. Wykonuj naukę codzienną każdego dnia, aby utrzymywać passę.</p>
		    <small>Ostatnia nauka: <strong><?php echo mb_strtolower(Config::getDiff(strtotime($lastStudy['last_time'])), 'UTF-8'); ?>.</strong></small>
		  </a>

		  <?php if($new) { ?>

		  <a href="<?php echo Config::getHome() ?>/?c=nauka&a=newf" class="list-group-item list-group-item-action flex-column align-items-start">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Nowe słówka</h5>
		    </div>
		    <p class="mb-1">Naucz się słówek, które jeszcze nie były ćwiczone.</p>
		    <small>Masz <?php echo $new; ?> nowych słówek.</small>
		  </a>

		  <?php } else { ?>

		  <a href="#" class="list-group-item list-group-item-action flex-column align-items-start disabled">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Nowe słówka</h5>
		    </div>
		    <p class="mb-1">Naucz się słówek, które jeszcze nie były ćwiczone.</p>
		    <small>Nie masz nowych słówek</small>
		  </a>

		  <?php } ?>

		  <a href="<?php echo Config::getHome() ?>/?c=nauka&a=solid" class="list-group-item list-group-item-action flex-column align-items-start">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Utrwal</h5>
		    </div>
		    <p class="mb-1">Powtarzaj słówka, które sprawiają Ci problem, aby zapamiętać je dobrze.</p>
		  </a>

		  <a href="<?php echo Config::getHome() ?>/?c=nauka&a=hardest" class="list-group-item list-group-item-action flex-column align-items-start">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Najtrudniejsze słówka</h5>
		    </div>
		    <p class="mb-1">Spróbuj nauczyć się słówek, których nie potrafisz przyswoić.</p>
		  </a>

		  <a href="<?php echo Config::getHome() ?>/?c=nauka&a=oldf" class="list-group-item list-group-item-action flex-column align-items-start">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Stare słówka</h5>
		    </div>
		    <p class="mb-1">Przypomnij sobie 20 fiszkek, które ćwiczyłeś najdawniej.</p>
		  </a>

		  <a href="<?php echo Config::getHome() ?>/?c=nauka&a=good" class="list-group-item list-group-item-action flex-column align-items-start">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Powtórz dobrze znane słówka</h5>
		    </div>
		    <p class="mb-1">Powtórz sobie 20 słówek, które znasz już bardzo dobrze.</p>
		  </a>

		  <a href="#" id="random-study" class="list-group-item list-group-item-action flex-column align-items-start">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Nauka losowych słówek.</h5>
		    </div>
		    <p class="mb-1">Powtórz wybraną ilość losowych słówek.</p>
		  </a>

		  <a href="<?php echo Config::getHome() ?>/?c=nauka&a=all" class="list-group-item list-group-item-action flex-column align-items-start">
		    <div class="d-flex w-100 justify-content-between">
		      <h5 class="mb-1">Wszystkie słówka</h5>
		    </div>
		    <p class="mb-1">Nauka wszystkich słówek naraz. </p>
		    <small>Liczba wszystkich słówek: <?php echo $all; ?></small>
		  </a>

		</div>

		<div class="modal fade" id="modal-random" tabindex="-1" role="dialog" aria-labelledby="Liczba słówek" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Liczba słówek</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						Podaj liczbę słówek
						<div class="form-group mt-2">
							<input id="random-count" type="number" placeholder="Np. 20" class="form-control" required>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Zamknij</button>
						<button id="random-study-go" type="button" class="btn btn-primary">Dalej</button>
					</div>
				</div>
			</div>
		</div>

		<?php

	}

}