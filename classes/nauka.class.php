<?php

class Nauka {

	public static function Create($arr) {

		if(isset($arr['title'])) $title = $arr['title']; else $title = 'Nauka';
		if(isset($arr['words'])) $words = $arr['words']; else $words = false;
		if(isset($arr['cat'])) $cat = $arr['cat']; else $cat = false;
		if(isset($arr['mode'])) $mode = $arr['mode']; else $mode = 'std';
		
		$cookies = new Cookies();
		$settings = $cookies->get('NaukaSettings');

		if($words) {

			$wordsJson = htmlspecialchars(json_encode($words));

			?>

			<input type="hidden" id="naukaSlowka" value="<?php echo $wordsJson; ?>">
			<input type="hidden" id="naukaTryb" value="<?php echo $mode ?>">

			<div id="naukaModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
					<div class="modal-content">
			    	<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle"><?php echo $title; ?></h5>
							<div class="nauka-progress"></div>
						</div>
						<div id="nauka-main" class="modal-body">

							Na następnych stronach przedstawione Ci zostaną słówka w języku polskim. Jeśli słówko będzie Tobie znane, kliknij <strong>"wiem"</strong>. W innym przypadku wybierz opcję <strong>"nie wiem"</strong>.<br><br>

							Skróty klawiszowe:<br>
							- wiem - strzałka w prawo,<br>
							- nie wiem - strzałka w lewo<br><br>

							Liczba słówek: <strong><?php echo sizeof($arr['words']); ?></strong>
							<?php
							if($cat) {
								?>
								<br>
								Kategoria: <strong><?php echo $cat; ?></strong>
								<?php
							}
							?>
							<br><br>


						</div>
						
						<div id="nauka-settings" class="modal-body">
							
														
							<h5>
								Ustawienia
							</h5>
							<div class="form-check">
								<?php

								if($settings) {



									?>

									<input <?php if($settings['reverseWords'] == 'on') echo 'checked'; ?> id="reverseWords" type="checkbox" name="reverseWords" class="form-check-input nauka-setting-option"><label for="reverseWords">Odwróć tryb nauki (język uczony -> język polski)</label><br>
									<input <?php if($settings['ttsAfter'] == 'on') echo 'checked'; ?> id="ttsAfter" type="checkbox" name="ttsAfter" class="form-check-input nauka-setting-option"><label for="ttsAfter">Automatyczne czytanie słówka po pokazaniu odpowiedzi</label><br>
									<input <?php if($settings['saveProgress'] == 'on') echo 'checked'; ?> id="saveProgress" type="checkbox" name="saveProgress" class="form-check-input nauka-setting-option"><label for="saveProgress">Zapisz postęp (wpływa na poziom słówek)</label>


									<?php

								} else {

									?>

									<input id="reverseWords" type="checkbox" name="reverseWords" class="form-check-input nauka-setting-option"> <label for="reverseWords">Odwróć tryb nauki (język uczony -> język polski)</label><br>
									<input id="ttsAfter" type="checkbox" name="ttsAfter" class="form-check-input nauka-setting-option"> <label for="ttsAfter">Automatyczne czytanie słówka po pokazaniu odpowiedzi</label><br>
									<input id="saveProgress" checked type="checkbox" name="saveProgress" class="form-check-input nauka-setting-option"> <label for="saveProgress">Zapisz postęp (wpływa na poziom słówek)</label>

									<?php

								}

								?>
							</div>

						</div>

						<div class="modal-footer">
							
							<div class="modal-footer-left" style="margin-right: auto;">
								<button id="nauka-settings-bttn" class="btn btn-muted">
									Ustawienia
								</button>
							</div>
							
							<div class="modal-footer-right">
								<a href="<?php echo Config::getHome() ?>/?c=nauka" class="btn btn-outline-secondary">Anuluj</a>
								<button id="zacznijNauke" class="btn btn-primary">Rozpocznij naukę</button>
							</div>							
						</div>
			    </div>
			  </div>
			</div>

			<?php

		} else {

			?>

			<div id="naukaModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
			    <div class="modal-content">
			    	<div class="modal-header">
				        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo $title; ?></h5>
				      </div>
			      	<div class="modal-body">
						
			      		Nie ma słówek spełniajacych dane kryteria.

					</div>

					<div class="modal-footer">
						<a href="<?php echo Config::getHome(); ?>/?c=nauka" class="btn btn-primary">Powrót do nauki</a>
					</div>
			    </div>
			  </div>
			</div>

			<?php

		} ?>

		<script>
			$('#naukaModal').modal({
				show:true,
				backdrop: 'static',
    			keyboard: false
			});
		</script>


		<?php

	}

	public static function checkIfStudied() {

		$db = new DB();
		$cookies = new Cookies();
		$lang = $cookies->getLang();

		$lastStudy = $db->select('last_time', 'study', 'user_id=:uid AND lang=:lang AND study_type = "std"', array(':uid'=>$_SESSION['user_id'], ':lang'=>$lang), 'one')['last_time'];

		if((date('Y-m-d', strtotime($lastStudy)) != date('Y-m-d')) && (date('Y-m-d', strtotime($lastStudy)) != date('Y-m-d', time()-86400)))
			$db->update('study', 'in_a_row=0', array(':uid'=>$_SESSION['user_id'], ':lang'=>$lang), 'user_id=:uid AND lang=:lang AND study_type="std"');

	}

}