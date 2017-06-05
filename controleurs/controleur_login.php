<?php
$db = App::getDatabase();
$auth = App::getAuth();
$auth->connectFromCookie($db);
if($auth->user()){
	App::Redirection('account.php');
} 
if(!empty($_POST) && !empty($_POST['login']) && !empty($_POST['mdp'])) {
	$user = $auth->login($db, htmlspecialchars($_POST['login']), htmlspecialchars($_POST['mdp']), isset($_POST['remember']));
	$session = Session::getInstance();
	if($user) {
		$session->setFlash('success', "vous etes desormais connecter");
		App::redirection('account.php');
	} else {
		$session->setFlash('danger', "identifiant ou mot de pass incorect");
		App::redirection('login.php');
	}
}
include 'vues/login.php';
?>