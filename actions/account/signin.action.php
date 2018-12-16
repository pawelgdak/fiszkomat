<?php

class SignIn_Action {

	public function SignIn() {

		if(isset($_SESSION['logged'])) Header::Home();

		if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['login'] == "true") {
	      
	      $this -> LogMe($_POST['email'], $_POST['password'], $_POST['remember']);
	      
	    }

		?>

		<form class="form-signin" action="" method="POST">
	      <a href="<?php echo Config::get('Locations/Home'); ?>"><span style="font-size: 64px; color: #333">Fiszkomat</span></a><br><br>

	      <input type="hidden" name="login" value="true">

	      <label for="inputEmail" class="sr-only">Adres email</label>
	      <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Adres email" required autofocus>

	      <label for="inputPassword" class="sr-only">Hasło</label>
	      <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Hasło" required>

	      <div class="checkbox mb-3">
	        <label>
	          <input type="checkbox" name="remember" value="remember"> Pamiętaj mnie
	        </label>
	      </div>

	      <button class="btn btn-lg btn-primary btn-block" type="submit">Zaloguj</button>

	      <br><p class="lead">Nie masz konta? <a href="<?php echo Config::get('Locations/Home') . '?c=account&a=signup'; ?>" class="link-dotted">Zarejestruj się.</a></p>
	      <p class="mt-5 mb-3 text-muted">&copy; 2018</p>
	    </form>
		
		<?php

	}

	public function LogMe($email, $pass, $remember) {

		if(!isset($email) || empty($email) || !isset($pass) || empty($pass)) {
	      Alert::Fail('Proszę wypełnić wszystkie pola!');
	      Header::Back();
	    } else {
	      
			$pass = md5(sha1($pass));
			$sth = DB::Connect() -> prepare('SELECT id FROM users WHERE email = :email AND password = :pass');
			$sth -> execute(array(':email' => $email, ':pass' => $pass));
			$user_id = $sth -> fetch()[0];
	      
	      if($sth->rowCount()) {
	        Alert::Pass('Zalogowano pomyślnie.');
	        MyAccount::LoggedIn($email, $user_id, $remember);
	      } else {
	        Alert::Fail('Podano złe dane.');
	        Header::Back();
	      }	      
	      
	    }

	}

}