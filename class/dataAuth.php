<?php

class dataAuth {

	private $options = [
		'restriction_msg' => "vous n'avez pas acces a cette page"
	];
	private $session;

	public function __construct($session, $options = []){
		$this->options = array_merge($this->options, $options);
		$this->session = $session;
	}

	public function hashPassword($password) {
		return hash('RIPEMD160', 'si tu aimes la wac tape dans tes mains' . $password);
	}

	public function delete($db, $value) {
		$user = $this->user();
		if($value == 0) {
			$db->query("UPDATE users SET active = 0 WHERE id = $user->id");
			$this->logout();
			return true;
		}
		$user = $db->query('SELECT * FROM users WHERE id = ?', [$user->id])->fetch();
		$this->connect($user);
		return false;
	}

	public function update($db, $login, $nom_complet, $email, $mdp){
		$user = $this->user();
		if($mdp != "") {
			$mdp = $this->hashPassword($mdp);
			$db->query("UPDATE users SET login = ?, nom_complet = ?, email = ?, mdp = ? WHERE id = $user->id", [ 
				$login, 
				$nom_complet,
				$email,
				$mdp,
			]);
		} else {
			$db->query("UPDATE users SET login = ?, nom_complet = ?, email = ? WHERE id = $user->id", [ 
				$login, 
				$nom_complet,
				$email,
			]);
		}
		$user = $db->query('SELECT * FROM users WHERE id = ?', [$user->id])->fetch();
		$this->connect($user);
	}

	public function register($db, $login, $nom_complet, $email, $mdp){
		$mdp = $this->hashPassword($mdp);
		$token = Str::random(60);
		$db->query("INSERT INTO users SET login = ?, nom_complet = ?, email = ?, mdp = ?, token = ?, certification = 1, banni = 0", [ 
			$login, 
			$nom_complet,
			$email,
			$mdp,
			$token
		]);
		$user_id = $db->lastInsertId();
		$to = $email;
		require_once 'vendor/autoload.php';
		if($_SERVER['SERVER_NAME'] == 'localhost'){
			$transport = Swift_SmtpTransport::newInstance('mailtrap.io', 25)
			->setUsername('your_mail')
			->setPassword('your_password');
		} else {
			$transport = Swift_MailTransport::newInstance();
		}
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance('confirmation de compte')
			->setFrom(['mail@epitech.eu' => 'epitech.eu'])
			->setTo(["$to"])
			->setBody("http://localhost/my_twitter\r\n/confirm.php?id=$user_id\r\n&token=$token");
		$result = $mailer->send($message);
	}

	public function confirm($db, $user_id, $token){
		$user = $db->query("SELECT * FROM users WHERE id = ?", [$user_id])->fetch();
		if($user && $user->token == $token) {	
			$db->query("UPDATE users SET token = NULL, date_inscription = NOW() WHERE id = ?", [$user_id]);
			$this->session->write('auth', $user);
			return true;
		} 
		return false;
	}

	public function restrict(){
		if(!$this->session->read('auth') || !($this->session->read('auth')->banni == 0)) {
			$this->session->setFlash('danger', $this->options['restriction_msg']);
			App::redirection('login.php');
			exit();
		}
	}

	public function user() {
		if(!$this->session->read('auth') || !($this->session->read('auth')->banni == 0)){
			return false;
		}
		return $this->session->read('auth');
	}

	public function connect($user){
		$this->session->write('auth', $user);
	}

	public function connectFromCookie($db) {
		if(isset($_COOKIE['remember']) && $this->user() && $this->user()->active == 1) {
			$remember_token = $_COOKIE['remember'];
			$parts = explode('==', $remember_token);
			$user_id = $parts[0];
			$user = $db->query('SELECT * FROM users WHERE id = ?', [$user_id])->fetch();
			if($user){
				$expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'ratonlaveurs');
				if($expected == $remember_token){
					$this->connect($user);
					setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
				} else {
					setcookie('remember', null, -1);
				}
			} else {
				setcookie('remember', null, -1);
			}
		}
	}

	public function login ($db, $login, $mdp, $remember) {
		$user = $db->query('SELECT * FROM users WHERE (login = :login OR email = :login) AND token IS NULL', ['login' => $login])->fetch();
		if((hash('RIPEMD160', 'si tu aimes la wac tape dans tes mains' . $mdp) == $user->mdp) && $user->banni == 0) {
			$this->connect($user);
			if($remember) {
				$this->remember($db, $user->id);
			}
			return $user;
		} else {
			return false;
		}
	}

	public function remember ($db, $user_id) {
		$remember_token = Str::random(250);
		$db->query('UPDATE users SET remember_token = ? WHERE id = ?', [$remember_token, $user_id]);
		setcookie('remember', $user_id . "==" . $remember_token . sha1($user_id . 'ratonlaveurs'), time() + 60 * 60 * 24 * 7);
	}

	public function logout () {
		$this->session->delete('auth');
		setcookie('remember', null, -1);
	}

	public function resetPassword($db, $email) {
		$user = $db->query('SELECT * FROM users WHERE email = ? AND token IS NULL', [$email])->fetch();
		if($user) {
			$token = Str::random(60);
			$db->query('UPDATE users SET token = ? WHERE email = ?', [$token, $user->email]);
			require_once 'vendor/autoload.php';
			if($_SERVER['SERVER_NAME'] == 'localhost'){
				$transport = Swift_SmtpTransport::newInstance('mailtrap.io', 25)
				->setUsername('your_mail')
				->setPassword('your_password');
			} else {
				$transport = Swift_MailTransport::newInstance();
			}
			$to = $email;
			$mailer = Swift_Mailer::newInstance($transport);
			$message = Swift_Message::newInstance('Reinitialisation de mot de passe')
				->setFrom(['mail@epitech.eu' => 'epitech.eu'])
				->setTo(["$to"])
				->setBody("http://localhost/my_twitter\r\n/reset.php?id={$user->id}\r\n&token=$token");
				$result = $mailer->send($message);
			return $user;
		} 
		return false;
	}

	public function checkReset($db, $user_id, $token) {
		return $db->query('SELECT * FROM users WHERE id = ? AND token = ? AND banni = 0', [$user_id, $token])->fetch();
	}
}