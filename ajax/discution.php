<?php
require_once '../class/bootstrap.php';
$db = App::getDatabase();
$auth = App::getAuth();
$auth->restrict();
$session = Session::getInstance();
$vue = new vue();
if(isset($_POST['discution']) || isset($_SESSION['dest'])) {
	if(isset($_POST['discution'])) {
		$_SESSION['dest'] = htmlspecialchars($_POST['discution']);
		$id_contact = htmlspecialchars($_POST['discution']);
	} else {
		$id_contact = $_SESSION['dest'];
	}
	
	$contact = $db->query("SELECT * FROM message WHERE (id_destinataire = ? AND id_expediteur = ?) OR (id_destinataire = ? AND id_expediteur = ? AND active = 1) ORDER BY date_message DESC", [$id_contact, $_SESSION['auth']->id, $_SESSION['auth']->id, $id_contact])->fetchall();
	if($contact) {
		foreach ($contact as $key => $value) {
			if ($value->id_expediteur == $_SESSION['auth']->id && $value->active == 1) {
				echo "<div class=\"expe\">";
				echo "<p onclick=\"deletMsg('" . $value->id_message . "')\" title=\"suprimer ce message\" style=\"cursor: pointer;\">x</p>";
				echo "<p>" . $value->date_message . "</p>";
				echo "<p>" . $value->message . "</p>";
				echo "</div>";	
			} 
			elseif($value->id_destinataire == $_SESSION['auth']->id && $value->active == 1) {
				echo "<div class=\"dest\">";
				echo "<p>" . $value->date_message . "</p>";
				echo "<p>" . $value->message . "</p>";
				echo "</div>";	
			}
		}
		exit();
	}
}