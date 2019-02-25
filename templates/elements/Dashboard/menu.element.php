<?php

class Menu_Element {

  private $items = array();

	public function Render() {

    $this->addItem('Strona główna', 'home', '', '', 'def');
    $this->addItem('Moje fiszki', 'book', 'fiszki');
    $this->addItem('Nauka', 'graduation-cap', 'nauka');
    $this->addItem('Wyloguj', 'sign-out-alt', 'account', 'logout');

		?>

    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">

        <ul class="nav flex-column">

          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted">
            <span>MENU</span>
          </h6>

          <?php $this->showItems(); ?>

          <?php $this->showFlaschards(); ?>

        </ul>
      </div>
    </nav>

    <nav class="mobile-nav">

        <ul class="nav">

          <?php $this->showItems(true); ?>

        </ul>

    </nav>

		<?php

  }

  private function addItem($text, $icon, $controller = '', $action = '', $additional = '') {

    array_push($this->items, array('icon' => $icon, 'text' => $text, 'controller' => $controller, 'action' => $action, 'additional' => $additional));

  }

  private function showFlaschards() {

    $db = new DB();
    $cookies = new Cookies();
    $lang = $cookies->getLang();
    $fiszki = $db->select('id, name', 'categories', 'user_id = :uid AND lang = :lang AND LENGTH(name) < 14 ORDER BY edit_date DESC LIMIT 10', array(':uid'=>$_SESSION['user_id'], ':lang'=>$lang));

    if($fiszki) {

      ?>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-3 mb-1 text-muted">
          <span>FISZKI</span>
        </h6>

      <?php

        foreach($fiszki as $fiszka) {

          ?>

            <li class="nav-item">
              <a class="menu-item nav-link" href="<?php echo Config::get('Locations/Home'); ?>/?c=fiszki&a=check&id=<?php echo $fiszka['id']; ?>">
                <i style="margin-right: 5px;" class="fas fa-caret-right fa-fw"></i>
                <span class="menu-item-text"><?php echo $fiszka['name']; ?></span>
              </a>
            </li>

          <?php

        }

        ?>



        <?php

    }

  }

  private function showItems($mobile = false) {

    isset($_GET['c']) ? $page = $_GET['c'] : $page = 'def';

    foreach($this->items as $item) {

      if($page == $item['controller'] || $item['additional'] == $page) $active = ' active'; else $active = '';

      if(!empty($item['action'])) $action = '&a=' . $item['action']; else $action = '';
      if(!empty($item['controller'])) $controller = '?c=' . $item['controller']; else $controller = '';

      ?>

      <li class="nav-item<?php echo $active; ?>">
        <a class="menu-item nav-link<?php echo $active; ?>" href="<?php echo Config::get('Locations/Home'); ?>/<?php echo $controller . $action; ?>">
          <?php if(!$mobile) { ?><i style="margin-right: 5px;" class="fas fa-<?php echo $item['icon']; ?> fa-fw"></i>
          <span class="menu-item-text"><?php echo $item['text']; ?></span>
          <?php } else { ?>
            <i style="margin-right: 5px;" class="fas fa-<?php echo $item['icon']; ?> fa-fw"></i>
          <?php } ?>
        </a>
      </li>

      <?php

    }

  }

}