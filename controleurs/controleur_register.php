<?php
Session::getInstance();
$vue = new vue();
$db = App::getDatabase();
if(isset($_SESSION["auth"])){
	header('Location: index.php');
}
if(!empty($_POST)) {
	$errors = array();

	$validator = new Validator($_POST);
	$validator->isAlphaNum("login","Votre pseudo n'est pas valide");
	if($validator->isValid()){
		$validator->isUnic("login", "users", $db, "Ce pseudo est deja pris");
	}
	$validator->isEmail("email","Votre email n'est pas valide");
	if($validator->isValid()){
		$validator->isUnic("email", "users", $db, "Cette email est deja pris");
	}
	$validator->isAlpha("nom_complet","Votre nom n'est pas valide");
	$validator->isConfirmed("mdp", "Votre mot de passe n'est pas valide");

	if($validator->isValid()) {
		App::getAuth()->register($db, htmlspecialchars($_POST['login']),
									htmlspecialchars($_POST['nom_complet']),
									htmlspecialchars($_POST['email']),
                                    htmlspecialchars($_POST['mdp']));
		Session::getInstance()->setFlash('success',"un email de confirmation vous a ete envoyer");
		App::redirection('index.php');
	} else {
		$errors = $validator->getErrors();
	}
}
include 'vues/register.php';
?>