<?php

class vue {

	static $instance;

	static function getInstance() {
		if(!self::$instance) {
			self::$instance = new vue();
		}
		return self::$instance;
	}

	public function affichage($db, $table, $field) {
		$arr = $db->query("SELECT $field FROM $table")->fetchall();
		foreach ($arr as $key => $value) {
			echo "<option value=\"" . $value->$field . "\">" . $value->$field . "</option>";
		}
	}

	public function afficheContact($db){
		$contact = $db->query("SELECT * FROM users WHERE login LIKE  \"%%\" AND id != ?", [$_SESSION['auth']->id])->fetchall();
		if($contact) {
			foreach ($contact as $key => $value) {
				echo "<div id=\"" . $value->id . "\" onclick=\"discution(document.getElementById('" . $value->id . "').id)\" style=\"cursor: pointer;\">";
				echo "<p>" . $value->login . "</p>";
				echo "</div>";
			}
		}
	}
	
}