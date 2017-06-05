<?php 
if(!empty($_POST) && !empty($_POST['email'])) {
	$db = App::getDatabase();
	$auth = App::getAuth();
	$session = Session::getInstance();
	if($auth->resetPassword($db, htmlspecialchars($_POST['email']))){
		$session->setFlash('success', "un email de reinitialisation vous a ete envoyer");
		App::Redirection("login.php");
		exit();
	} else {
        var_dump($_POST['email']);
        die();  
		$session->setFlash('danger', "Aucun email ne corespond");
		App::Redirection('login.php');
	}			
}
?>