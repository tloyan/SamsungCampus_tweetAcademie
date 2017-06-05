<?php
require_once '../class/bootstrap.php';
$db = App::getDatabase();
$auth = App::getAuth();
$session = Session::getInstance();
$vue = new vue();

if(isset($_POST['delMsg'])) {
	$db->query("UPDATE message SET active = 0 WHERE id_message = ? AND id_expediteur = ?", [htmlspecialchars($_POST['delMsg']), $_SESSION['auth']->id]);
		exit();
}