<?php
require_once '../class/bootstrap.php';
$db = App::getDatabase();
$auth = App::getAuth();
$auth->restrict();
$session = Session::getInstance();
$vue = new vue();
if(isset($_POST['contact'])) {
	$contact = $db->query("SELECT * FROM users WHERE login LIKE  \"%" . htmlspecialchars($_POST['contact']) . "%\" AND id != ?", [$_SESSION['auth']->id])->fetchall();
	if($contact) {
		foreach ($contact as $key => $value) {
			echo "<div id=\"" . $value->id . "\" onclick=\"discution(document.getElementById('" . $value->id . "').id)\">";
			echo "<p>" . $value->login . "</p>";
			echo "</div>";
		}
		exit();
	}
}