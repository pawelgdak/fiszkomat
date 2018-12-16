<?php

class SignUp_Action {

	public function SignUp() {

		if(isset($_SESSION['logged'])) Header::Home();

		if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['register'] == "true") {

			// Rejestracja
			$this->RegisterMe($_POST['email'], $_POST['password'], $_POST['password_repeat']);

		} else {

			?>

			<form class="form-signup" action="?c=account&a=signup" method="POST">
		      <a href="<?php echo Config::get('Locations/Home'); ?>"><span style="font-size: 64px; color: #333">Fiszkomat</span></a><br><br>

		      <input type="hidden" name="register" value="true">

		      <label for="inputEmail" class="sr-only">Adres email</label>
		      <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Adres email" required autofocus>

		      <label for="inputPassword" class="sr-only">Hasło</label>
		      <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Hasło" required>

		      <label for="inputPassword" class="sr-only">Powtórz hasło</label>
		      <input type="password" name="password_repeat" id="inputPassword" class="form-control" placeholder="Powtórz hasło" required>

		      <br><br><button class="btn btn-lg btn-primary btn-block" type="submit">Zarejestruj</button>
		      <br><p class="lead">Masz już konto? <a href="<?php echo Config::get('Locations/Home') . '?c=account&a=signin'; ?>" class="link-dotted">Zaloguj się.</a></p>
		      <p class="mt-5 mb-3 text-muted">&copy; 2017-2018</p>
		    </form>

			<?php

		}

	}

	private function RegisterMe($email, $pass, $pass_r) {
		if(!isset($pass) || empty($pass) || !isset($pass_r) || empty($pass_r) || !isset($email) || empty($email)) {
			Alert::Fail('Proszę wypełnić wszystkie pola!');
			Header::Back();
	    } else {

	    	if(strlen($email) > 80) {
	    		Alert::Fail('Podany email jest za długi.');
	    		Header::Back();
	    	} elseif($pass != $pass_r) {
				Alert::Fail('Podane hasła różnią się od siebie.');
				Header::Back();
			} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				Alert::Fail('Podano zły adres email.');
				Header::Back();
			} else {

				$sth = DB::Connect() -> prepare('SELECT id FROM users WHERE email = :email');
				$sth -> execute(array(':email' => $email));

				if($sth->rowCount()){
					Alert::Fail('Podany adres email jest zajęty.');
					Header::Back();
				} else {
				    
				    $token = md5(sha1(rand()));
				    				                      
					$pass = md5(sha1($pass));
					$sth = DB::Connect() -> prepare('INSERT INTO users (password, email) VALUES (:pass, :email)');
					$sth -> execute(array(':pass' => $pass, ':email' => $email));

					$sth = DB::Connect() -> prepare('SELECT id FROM users WHERE email = :email');
					$sth -> execute(array(':email'=>$email));
					$user_id = $sth -> fetch()[0];

					History::Insert("Rejestracja", $user_id);

					Alert::Pass('Pomyślnie zarejestrowano.');
					Header::Home('?c=account&a=signin');

				}
			}

	    }
	}

}