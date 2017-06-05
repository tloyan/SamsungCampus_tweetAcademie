<?php 
$db = App::getDatabase();
$auth = App::getAuth();
$auth->restrict();
$session = Session::getInstance();
$vue = new vue();
if(!empty($_POST)) {
	$errors = array();

	$db = App::getDatabase();
	$validator = new Validator($_POST);
	$validator->isAlphaNum("login","Votre pseudo n'est pas valide");
	$validator->isEmail("email","Votre email n'est pas valide");
	$validator->isAlpha("nom_complet","Votre nom n'est pas valide");
	$validator->isConfirmedUp("mdp", "Votre mot de passe n'est pas valide");
	$errors = $validator->getErrors();
	if($validator->isValid()) {
		$auth->update($db, htmlspecialchars($_POST['login']),
						htmlspecialchars($_POST['nom_complet']),
						htmlspecialchars($_POST['email']),
						htmlspecialchars($_POST['mdp']));
		$session->setFlash('success',"Vos modif on bien ete pris en conte");
		$session = Session::getInstance();
		if($auth->delete($db, $_POST['submit'])) {
			App::redirection('login.php');	
		}
		App::redirection('account.php');
	} else {
		$errors = $validator->getErrors();
	}
}
if(empty($_GET) || $_GET['display'] == 'tweet'){
	$tweets = $tweet->getMyTweets($_SESSION['auth']->id, $_SESSION['auth']->nom_complet);
}
if(isset($_GET['display']) && $_GET['display'] == 'follow'){
	$follows = $follow->getFollowers($_SESSION['auth']->id);
}
include 'vues/account.php';
?>