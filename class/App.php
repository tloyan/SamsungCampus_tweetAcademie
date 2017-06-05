<?php

class App {

	static $db;

	static function getDatabase() {
		if(!self::$db){
			self::$db = new Database('root', '', 'twitter');
		}
		return self::$db;
	}

	static function getAuth() {
		return new dataAuth(Session::getInstance());
	}

	static function redirection($param) {
		header("Location: $param");
		exit();
	}
}