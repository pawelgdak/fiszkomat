let modal = $('#naukaModal');
let mode = $('#naukaTryb').val();
let notStarted = true;
let page = 'notStarted';

/**
 * Shuffles array in place. ES6 version
 * @param {Array} a items An array containing the items.
 */
function shuffle(a) {
    for (let i = a.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
}

let ustawienia = $('#nauka-settings');
let ustawieniaBttn = $('#nauka-settings-bttn');
let content = $('#nauka-main');
let isMain = true;
ustawienia.hide();

ustawieniaBttn.on('click', function(){
	if(isMain) {
		ustawienia.show();
		isMain = false;
		content.hide();
		ustawieniaBttn.text('Wróć');
	} else {
		isMain = true;
		ustawienia.hide();
		content.show();
		ustawieniaBttn.text('Ustawienia');
	}

})

$('#random-study').click(function(){
	
	$('#modal-random').modal('toggle');
	
	$('#random-study-go').click(function(){
		let count = $('#random-count').val();
		
		window.location = 'http://fiszkomat.pawelgdak.pl/?c=nauka&a=random&count=' + count;
		
	})
	
})

let settings = {};

// USTAWIENIA
$('.nauka-setting-option').each(function(){
	if($(this).is(":checked")) settings[$(this).attr("name")] = 'on'; else settings[$(this).attr("name")] = 'off';
})

if($('.nauka-setting-option[name="reverseWords"]').is(":checked")) settings.reverseWords = 'on'; else settings.reverseWords = 'off';
if($('.nauka-setting-option[name="ttsAfter"]').is(":checked")) settings.ttsAfter = 'on'; else settings.ttsAfter = 'off';
if($('.nauka-setting-option[name="saveProgress"]').is(":checked")) settings.saveProgress = 'on'; else settings.saveProgress = 'off';

$('.nauka-setting-option').on('click', function(){

	let type = $(this).attr('name');
	if($(this).is(":checked")) settings[$(this).attr("name")] = 'on'; else settings[$(this).attr("name")] = 'off';

	$.post('functions/Fiszki.php', {method:"changeSettings", type:type, value:settings[$(this).attr("name")]}, function(x){console.log(x)})

})

if(typeof(modal) !== 'undefined') {

	let nauka = {}

	nauka.flashcard = function() {

		page = 'flashcard';

		this.body.html(fiszkaContent(this.words[this.currentWord]));
		this.footer.html(pokazOdpowiedz(this.possibleEnd));
		this.progress.html(progress(this.count, this.wrongCount, this.goodCount));

	}

	nauka.showAnswer = function() {
		
		page = 'answer';

		this.body.html(fiszkaAnswer(this.words[this.currentWord]));
		this.footer.html(pokazWybor);
		
		if(settings.ttsAfter == 'on') {
			$('.textToSpeech').trigger("click");
		}

	}

	nauka.iknow = function() {

		page = 'answer';
		this.goodCount++;

		if(this.endOfFirst) {

			this.goodWords.push(this.words[this.currentWord]);

		}

		if(this.currentWord >= this.count-1) this.endOfRound();
		else {

			this.currentWord++;
			this.flashcard();

		}

	}

	nauka.idontknow = function() {

		page = 'answer';

		this.wrongWords.push(this.words[this.currentWord]);
		this.wrongCount++;

		if(this.currentWord >= this.count-1) this.endOfRound();
		else {

			this.currentWord++;
			this.flashcard();

		}

	}

	nauka.continue = function() {

		this.words = this.wrongWords;
		shuffle(this.words);
		this.wrongWords = [];
		this.wrongCount = 0;
		this.goodCount = 0;
		this.currentWord = 0;
		this.count = this.words.length;

		this.flashcard();

	}

	nauka.endOfStudy = function() {

		page = 'end';

		this.body.html(pokazKoniec);
		this.footer.html(pokazPowrot(this.mode));
		this.progress.html('');
		
		if(settings.saveProgress == 'on') {
			$.post('functions/nauka.php', {good:this.goodWords, wrong:this.allWrongs, mode:this.mode, lang:this.lang}, function(x){
				//console.log(x);
			})
		}

	}

	nauka.endOfRound = function() {

		page = 'endRound';

		if(this.wrongWords.length > 0) {

			if(this.endOfFirst) {

				this.progress.html('');
				this.allWrongs = this.wrongWords;
				this.possibleEnd = true;
				this.endOfFirst = false;
				this.body.html(koniecRundyPierwszej(this.count-this.wrongWords.length, this.wrongWords.length));
				this.footer.html(pokazKontynuacje);

			} else {

				this.continue();

			}

		} else {

			this.endOfStudy();

		}

	}

	nauka.start = function(modal, words, mode) {

		this.words = JSON.parse(words);
		shuffle(this.words);

		this.count = this.words.length;
		this.currentWord = 0;
		this.wrongWords = [];
		this.goodWords = [];
		this.possibleEnd = false;
		this.mode = mode;
		this.allWrongs = [];
		this.lang = this.words[this.currentWord].lang;

		this.goodCount = 0;
		this.wrongCount = 0;

		this.endOfFirst = true;

		this.modal = modal;
		this.body = content;
		this.footer = this.modal.find('.modal-footer-right');
		this.progress = this.modal.find('.nauka-progress');
		
		if(!isMain) {
			isMain = true;
			ustawienia.hide();
			content.show();
		}
		
		$('.modal-footer-left').remove();

		this.flashcard();

	}

	$('#zacznijNauke').on('click', function(){
		nauka.start(modal, $('#naukaSlowka').val(), mode);
	});

	$(modal).on('click', '.pokazOdpowiedz', function() {
		
		nauka.showAnswer();

	}).on('click', '.odpowiedzWiem', function() {

		nauka.iknow();

	}).on('click', '.odpowiedzNieWiem', function() {

		nauka.idontknow();

	}).on('click', '.kontynuujNauke', function() {

		nauka.continue();

	}).on('click', '.zakonczNauke', function() {

		nauka.endOfStudy();

	}).on('click', '.powrotDoFiszek', function() {

		window.location = 'http://fiszkomat.pawelgdak.pl/?c=fiszki';

	}).on('click', '.powrotDoNauki', function() {

		window.location = 'http://fiszkomat.pawelgdak.pl/?c=nauka';

	}).on('keydown', function(x){

		let code = x.keyCode;

		switch(page) {

			case 'flashcard':
				if(code == 13 || code == 32)
					nauka.showAnswer();
				break;
			case 'answer':
				if(code == 39) nauka.iknow();
				else if(code == 37) nauka.idontknow();
				break;
			case 'notStarted':
				if(code == 13 || code == 32) {
					notStarted = false;
					nauka.start(modal, $('#naukaSlowka').val(), mode);
				}
				break;
			case 'endRound':
				if(code == 13 || code == 32) nauka.continue();
				else if(code == 27) nauka.endOfStudy();
				break;
			case 'end':
				if(code == 13 || code == 32 || code == 27)
					window.location = 'http://fiszkomat.pawelgdak.pl/?c=nauka';

		}
	})
	
	
	

}

function pokazOdpowiedz(end) {

		let endOut;

		if(end) endOut = '<button class="zakonczNauke btn btn-secondary">Zakończ</button> ';
		else endOut = '';

		return endOut + '<button class="pokazOdpowiedz btn btn-primary">Pokaż odpowiedź</button>';

	}
	
	function pokazKontynuacje() {

		return '<button class="zakonczNauke btn btn-secondary">Zakończ</button> <button class="kontynuujNauke btn btn-primary">Kontynuuj</button>';

	}

	function progress(count, wrong, good) {

		return `

			<div class="progress">
				<div class="progress-bar bg-success" style="width:`+(good/count)*100+`%">

				</div>
				<div class="progress-bar bg-danger" style="width:`+(wrong/count)*100+`%">

				</div>
			</div>

		`;

	}

	function pokazWybor() {

		return '<button class="odpowiedzNieWiem btn btn-danger">Nie wiem</button> <button class="odpowiedzWiem btn btn-success">Wiem</button>';

	}

	function fiszkaContent(word) {

		if(settings.reverseWords == 'off') {

			return `

			<div class="nauka-slowka"><h1 class="">`+word.pl+` <i class="fas fa-volume-down ml-1 textToSpeech" data-text="`+word.pl+`" data-lang="pl"></i></h1></div>

			`;

		} else {

			return `

			<div class="nauka-slowka"><h1 class="">`+word.fo+` <i class="fas fa-volume-down ml-1 textToSpeech" data-text="`+word.fo+`" data-lang="`+word.lang+`"></i></h1></div>

			`;

		}

	}

	function fiszkaAnswer(word) {

		let note;

		if(word.note.length > 0) note = '<center><p class="mt-5"><strong>Notatka: </strong>'+word.note+'</p></center>';
		else note = '';

		if(settings.reverseWords == 'off') {

			return `

			<div class="nauka-slowka"><h1 class="">`+word.fo+` <i class="fas fa-volume-down ml-1 textToSpeech" data-text="`+word.fo+`" data-lang="`+word.lang+`"></i></h1></div>


			` + note;

		} else {

			return `

			<div class="nauka-slowka"><h1 class="">`+word.pl+` <i class="fas fa-volume-down ml-1 textToSpeech" data-text="`+word.pl+`" data-lang="pl"></i></h1></div>


			` + note;

		}

	}

	function koniecRundyPierwszej(wordsOk, wordsLeft) {

		return `

		<center class="mt-3"><h1>Ukończyłeś pierwszy etap!</h1></center>
		<p class="mt-5">Znałeś dobrze <strong>`+wordsOk+`</strong> słówek.</p><br>
		<p>W następnych etapach możesz powtórzyć słówka, których nie umiałeś. Możesz też zakończyć w tym miejscu, jeśli nie chcesz już kontynuować nauki.</p>
		<small>Udzielenie dobrych odpowiedzi w następnych etapach nie podniesie poziomu znajomości słówek w systemie.</small>

		`;

	}

	function pokazKoniec() {

		return `

		<center class="mt-3"><h1>Gratulacje! Ukończyłeś naukę!</h1></center>
		<p class="mt-5">Możesz teraz powrócić i uczyć się innych nowych słówek lub utrwalać już te znane!</p>

		`;

	}

	function pokazPowrot(mode) {

		if(mode == 'cat')
			return '<button class="powrotDoFiszek btn btn-primary">Powrót do fiszek</button>';
		else return '<button class="powrotDoNauki btn btn-primary">Powrót do nauki</button>';

	}


