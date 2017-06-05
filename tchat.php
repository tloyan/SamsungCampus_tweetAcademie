<?php 

$db = App::getDatabase();
$auth = App::getAuth();
//$auth->restrict();
$session = Session::getInstance();
$vue = new vue();
$search = new search($_POST);
?>
<div id="tchat">
	<div id="left">
		<form id="tchat" onsubmit="return false">
			<div id="enterLogin">
				<label>Pseudo</label>
				<input type="text" name="pseudo" id="form_pseudo" autocomplete="off" onkeyup="search_contact(this.value)" />
			</div>
		</form>
		<div id="contact">
		<?php $vue->afficheContact($db); ?>
		</div>
	</div>
	<div id="right">
		<div id="message">
		</div>
		<form id="form">
			<div>
				<textarea id="area"></textarea>
			</div>
			<div onclick="insertMsg($('#area').val())">envoyer</div>
		</form>
	</div>
</div>
<div id="tchatAction">
	<div id="tchatRetract" onclick="display()">tchat</div>
</div>