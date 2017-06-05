<?php

class Session {

	static $instance;

	static function getInstance() {
		if(!self::$instance){
			self::$instance = new Session();
		}
		return self::$instance;
	}

	public function __construct(){
		session_start();
	}

	public function setFlash($type, $message) {
		$_SESSION['flash'][$type] = $message;
	}

	public function hasFlash(){
		return isset($_SESSION['flash']);
	}

	public function getFlash(){
		$flash = $_SESSION['flash'];
		unset($_SESSION['flash']);
		return $flash;
	}

	public function write($key, $value){
		$_SESSION[$key] = $value;
	}

	public function read($key){
		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}

	public function delete($key){
		unset($_SESSION[$key]);
	}
}