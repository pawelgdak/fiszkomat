<?php

class Title_Element {
	
	public function Render($arr) {

		?>

		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
			<h1 class="h2"><?php if(isset($arr['title'])) echo $arr['title']; ?></h1>

			<?php if(isset($arr['add'])) { ?>

			<div class="btn-toolbar mb-2 mb-md-0">
				<?php

				echo $arr['add'];

				?>
			</div>

			<?php } ?>

		</div>

		<?php

	}

}