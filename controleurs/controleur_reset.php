<?php
if(isset($_GET['id']) && isset($_GET['token'])) {
	$auth = App::getAuth();
	$db = App::getDatabase();
	$session = Session::getInstance();
	$user = $auth->checkReset($db, htmlspecialchars($_GET['id']), htmlspecialchars($_GET['token']));
	if($user) {
		if(!empty($_POST)){
			$validator = new Validator($_POST);
			$validator->isConfirmed('mdp', "");
			if($validator->isValid()){
				$mdp = $auth->hashPassword(htmlspecialchars($_POST['mdp']));
				$db->query('UPDATE users SET mdp = ?, token = NULL WHERE id = ?', [$mdp, $user->id]);
				$session->setFlash('access', "votre mot de passe a bien ete comfirmer");
				$auth->connect($user);
				App::Redirection('account.php');
				exit();
			}	
		} 
	} else {
			$session->setFlash('danger', "ce token n'est pas valide");
			App::Redirection('login.php');
			exit();
	}
} else {
		App::Redirection('login.php');
}
?>
