<?php
require_once '../class/bootstrap.php';
$db = App::getDatabase();
$auth = App::getAuth();
$session = Session::getInstance();
$vue = new vue();

if(isset($_POST['insMsg'])) {
	$db->query("INSERT INTO message (id_expediteur, message, id_destinataire) VALUES 
		(?, ?, ?)", [$_SESSION['auth']->id, htmlspecialchars($_POST['insMsg']), $_SESSION['dest']]);
	echo "<div>";
	echo "<textarea id=\"area\">";
	echo "</textarea>";
	echo "</div>";
	echo "<div name=\"submit\" onclick=\"insertMsg($('#area').val())\">envoyer</div>";
}