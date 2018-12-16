<?php

class Profile_Element {
	
	public function Render() {

		$db = new DB();
		$cookies = new Cookies();
		$lang = $cookies->getLang();

		$slowka = $db->policz('words', 'lang=:lang AND user_id=:uid AND level < 4 AND level != 0', array(":lang"=>$lang, ':uid'=>$_SESSION['user_id']));

		$inrow = $db->select('in_a_row', 'study', 'study_type = "std" AND user_id=:uid AND lang=:lang', array(':uid'=>$_SESSION['user_id'], ':lang'=>$lang), 'one')['in_a_row'];

        if(!$inrow) $inrow = 0;
        if(!$slowka) $slowka = 0;

		?>


			<a href="#" title="Ilość dni nauki z rzędu" style="text-decoration: inherit; color: inherit;"><i class="fas fa-fire fa-fw"></i> <?php echo $inrow; ?></a>
			<small>&#8226;</small>
			<a href="#" title="Ilość słówek znanych w dobrym stopniu" style="text-decoration: inherit; color: inherit;"><i class="fas fa-check fa-fw"></i> <?php echo $slowka; ?></a>

			<a href="<?php echo Config::getHome(); ?>/?c=jezyk"><img class="lang-image" src="<?php echo Config::getHome(); ?>/assets/images/flags/<?php echo $lang; ?>.png" style="width: 24px; " class="ml-2"></a>

		<?php

	}

}