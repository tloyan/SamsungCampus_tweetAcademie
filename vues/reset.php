<?php require_once 'header.php';
require_once 'controleurs/controleur_reset.php'; ?>

<h1>Reinisitalisation de mot de passe</h1>

<form action="" method="POST">
	<div class="form-group">
		<label for="">mot de pass</label>
		<input type="password" name="mdp" class="form-control" />
	</div>
	<div class="form-group">
		<label for=""> confirmation Mot de passe</label>
		<input type="password" name="mdp_confirm" class="form-control" />
	</div>
	<button type="submit" class="btn btn-primary">Reinitialisation</button>
</form>