<?php

class Fiszkomat {

	static function Load($page) {

		isset($_GET['a']) ? $action = $_GET['a'] : $action = 'Main';
		$fileDir = Config::get('Directories/Controllers') . $page . '.controller.php';

        $error = new ErrorClass();

		if(file_exists(strtolower($fileDir))) {

			require(strtolower($fileDir));
			$controllerClassName = $page . '_Controller';

			if(class_exists($controllerClassName)) {

				// Page template

				$p = new $controllerClassName();
				$actionDir = Config::get('Directories/Actions') . '/' . $page . '/' . $action . '.action.php';
				$actionClass = false;

				if(method_exists($p, $action)) $actionClass = $p;
				elseif(file_exists(strtolower($actionDir))) {

					$actionClassName = $action . '_Action';

					include(strtolower($actionDir));
					$actionClass = new $actionClassName;

				} else $error->setCode('404');

				if($actionClass) {

					$template = $p -> getTemplate();
					$template -> setTitle($p, $action);
					if($css = $p->insertCustomCss()) $template -> insertCss($css);
					if($js = $p->insertCustomJs()) $template -> insertJs($js);
					if(!$p->checkIfAjax($action)) {
						$template -> HtmlBegin();
						$template -> Header();
					}
					$actionClass -> $action();
					if(!$p->checkIfAjax($action)) {
						$template -> Footer();
						$template -> HtmlEnd();
					}


				} else $error->setCode('404');

			} else $error->setCode('404');

		} else $error->setCode('404');

		$error->check();

	}

	static function Index($page) {

		?>

		<?php Self::Load($page); ?>

		<?php

	}

}