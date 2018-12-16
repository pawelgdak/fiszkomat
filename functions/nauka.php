<?php

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	include('../init.php');
	nauka();
} else exit;

function nauka() {

	$uid = $_SESSION['user_id'];
	$db = new DB();
	$time = date('Y-m-d H:i:s');
	$lang = Config::fixLang($_POST['lang']);

	if($_POST['mode'] && $_POST['mode'] != 'cat') {

		$lastStudy = $db->select('id, last_time, in_a_row', 'study', 'user_id=:uid AND lang=:lang AND study_type=:mode', array(":uid"=>$uid, ":lang"=>$lang, ":mode"=>$_POST['mode']), 'one');

		$last = $db->select('last_time, in_a_row', 'study', 'user_id=:uid AND lang=:lang AND study_type=:mode', array(":uid"=>$uid, ":lang"=>$lang, ":mode"=>$_POST['mode']), 'one');

		if($lastStudy) {

			if(date('Y-m-d', strtotime($lastStudy['last_time'])) != date('Y-m-d'))
				$inRow = $lastStudy['in_a_row'] + 1;
			else $inRow = $lastStudy['in_a_row'];

			$db->update('study', 'last_time=:time, in_a_row=:inrow', array(":time"=>$time, ':inrow'=>$inRow, "id"=>$lastStudy['id']), 'id=:id');

		} else {

			$db->insert('study', 'user_id, lang, study_type, in_a_row', ':uid, :lang, :type, 1', array(':uid'=>$uid, ':lang'=>$lang, ':type'=>$_POST['mode']));

		}

	}

	if(isset($_POST['good'])) {

		foreach($_POST['good'] as $word) {

			$row = $db->select('last_answer, good_in_a_row, level', 'words', 'id=:id AND user_id=:uid', array(':id'=>$word['id'], ':uid'=>$uid), 'one');

			if(!$row['last_answer']) $lastAnswer = 0;
			else $lastAnswer = strtotime($time) - strtotime($row['last_answer']);

			$newLevel = action($row['level'], $row['good_in_a_row'], $lastAnswer / 3600, true);
			$newGoodInRow = $row['good_in_a_row'] + 1;
			$db->update('words', 'last_answer=:last, level=:level, good_in_a_row=:gir', array(':last'=>$time, ':id'=>$word['id'], ':uid'=>$uid, ":level"=>$newLevel, ":gir"=>$newGoodInRow), 'id=:id AND user_id=:uid');

		}

	}

	if(isset($_POST['wrong'])) {

		foreach($_POST['wrong'] as $word) {

			$row = $db->select('last_answer, good_in_a_row, level', 'words', 'id=:id AND user_id=:uid', array(':id'=>$word['id'], ':uid'=>$uid), 'one');

			if(!$row['last_answer']) $lastAnswer = 0;
			else $lastAnswer = strtotime($time) - strtotime($row['last_answer']);

			$newLevel = action($row['level'], $row['good_in_a_row'], $lastAnswer / 3600, false);
			$newGoodInRow = 0;
			$db->update('words', 'last_answer=:last, level=:level, good_in_a_row=:gir', array(':last'=>$time, ':id'=>$word['id'], ':uid'=>$uid, ":level"=>$newLevel, ":gir"=>$newGoodInRow), 'id=:id AND user_id=:uid');

		}

    }
    
    if(isset($_POST['mode'])) $mode = $_POST['mode']; else $mode = 'unknown';
    History::Insert("Nauka", $_SESSION['user_id'], $mode);

}

function action($level, $goodInRow, $lastAnswer, $answer) {

	switch($level) {

		case '0':
		case '5':
			if($answer) {
				return 4;
			} else return 6;
			break;
		case '4':
			if($answer) {
				if($lastAnswer > 1) return 3;
				else {
					if($goodInRow > 2) return 3;
					else return 4;
				}
			} else {
				if($lastAnswer > 1) return 4;
				else return 5;
			}
			break;
		case '3':
			if($answer) {
				if($lastAnswer>12) return 2;
				elseif($lastAnswer>3 && $lastAnswer<6) {
					if($goodInRow>7) return 2;
					else return 3;
				} else {
					if($goodInRow<5) return 3;
					else return 2;
				}
			} else {
				return 4;
			}
			break;
		case '2':
			if($answer) {
				if($lastAnswer>48) {
					if($goodInRow<3) return 2;
					else return 1;
				} else return 2;
			} else return 3;
			break;
		case '1':
			if($answer) return 1;
			else return 2;
			break;
		case '6':
			if($answer){
				if($lastAnswer>1) return 4;
				else return 5;
			} else {
				if($lastAnswer>1) return 7;
				else return 6;
			}
			break;
		case '7':
			if($answer) {
				if($lastAnswer>1) return 6;
				else {
					if($goodInRow<3) return 7;
					else return 6;
				}
			} else {
				if($lastAnswer>12) return 8;
				else return 7;
			}
			break;
		case '8':
			if($answer){
				if($lastAnswer>12) return 7;
				else {
					if($goodInRow<5) return 8;
					else return 6;
				}
			} else {
				if($lastAnswer>12) return 9;
				else return 8;
			}
			break;
		case '9':
			if($answer) return 8;
			else return 9;
			break;
		default:
			return 5;

	}

}