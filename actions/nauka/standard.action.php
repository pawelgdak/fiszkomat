<?php

class Standard_Action {

	public $end;
	public $newArr = array();
	public $all = array();
	public $arr = array();

	public function Standard() {

		$db = new DB();
		$cookies = new Cookies();
		$lang = $cookies->getLang();

		$this->all = $db->select('id, first as pl, second as fo, description as note, level, last_answer, lang', 'words', 'user_id = :uid AND lang = :lang', array(':uid'=>$_SESSION['user_id'], ":lang"=>$lang));

		$this->arr = $this->all;

		$this->generate();

		?>
		
		Jeśli nie wyskoczyło Ci okienko z nauką słówek, spróbuj odświeżyć stronę.


		<?php Nauka::Create(array('title'=>'Standardowa nauka', 'words'=>$this->newArr)); ?>

		<?php

	}

	public function generate() {

		if(sizeof($this->arr) < 30) {
			$this->newArr = $this->arr;
			return true;
		}

		$second = false;

		while(true) {

			usort($this->all, function($a, $b){
				return $b['level'] - $a['level'];
			});

			for($i=0; $i<10; $i++) {
				if(isset($this->all[$i])) {
					if($this->all[$i]['level'] > 5) {

						array_push($this->newArr, $this->all[$i]);
						unset($this->all[$i]);


					} else {
						
						break;
						
					}
				} else {

					break;

				}
			}

			$this->all = array_values($this->all);

			if(sizeof($this->newArr) > 30) break;

			$temp = $this->all;
			shuffle($temp);
			$i = 1;

			foreach($temp as $t) {

				if($t['level'] > 5) {

					array_push($this->newArr, $t);
					$klucz = array_search($t, $this->all);
					unset($this->all[$klucz]);
					$i++;

				}

				if($i==10) break;

			}

			$this->all = array_values($this->all);

			if(sizeof($this->newArr) > 30) break;

			$temp = $this->all;
			shuffle($temp);
			$i = 1;

			foreach($temp as $t) {

				if($t['level'] == 4 || $t['level'] == 5) {

					array_push($this->newArr, $t);
					$klucz = array_search($t, $this->all);
					unset($this->all[$klucz]);
					$i++;

				}

				if($i==10) break;

			}

			$this->all = array_values($this->all);

			if(sizeof($this->newArr) > 30) break;

			$temp = $this->all;
			shuffle($temp);
			$i = 1;

			foreach($temp as $t) {

				if($t['level'] == 0) {

					array_push($this->newArr, $t);
					$klucz = array_search($t, $this->all);
					unset($this->all[$klucz]);
					$i++;

				}

				if($i==5) break;

			}

			$this->all = array_values($this->all);

			if(sizeof($this->newArr) > 30) break;

			$temp = $this->all;
			shuffle($temp);
			$i = 1;

			foreach($temp as $t) {

				if($t['level'] == 3) {

					array_push($this->newArr, $t);
					$klucz = array_search($t, $this->all);
					unset($this->all[$klucz]);
					$i++;

				}

				if($i==3) break;

			}

			$this->all = array_values($this->all);
			
			if(sizeof($this->newArr) > 20) break;

			$temp = $this->all;
			shuffle($temp);
			$i = 1;

			foreach($temp as $t) {

				if($t['level'] == 1 || $t['level'] == 2) {

					array_push($this->newArr, $t);
					$klucz = array_search($t, $this->all);
					unset($this->all[$klucz]);
					$i++;

				}

				if($i==3) break;

			}

			$this->all = array_values($this->all);
			$second = true;

		}


	}

}