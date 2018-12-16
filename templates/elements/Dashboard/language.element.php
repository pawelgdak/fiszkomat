<?php

class Language_Element {
	
	public function Render() {

		$cookies = new Cookies();
        $lang = $cookies->get('Language');

		?>

		<div class="selectLanguage">
			<select id="pick-language">
				<option></option>
				<option value="en"<?php if($lang=='en') echo ' selected'; ?>>Angielski</option>
				<option value="es"<?php if($lang=='es') echo ' selected'; ?>>Hiszpański</option>
				<option value="de"<?php if($lang=='de') echo ' selected'; ?>>Niemiecki</option>
				<option value="fr"<?php if($lang=='fr') echo ' selected'; ?>>Francuski</option>
				<option value="it"<?php if($lang=='it') echo ' selected'; ?>>Włoski</option>
				<option value="ru"<?php if($lang=='ru') echo ' selected'; ?>>Rosyjski</option>
			</select>
		</div>

		<?php

	}

}