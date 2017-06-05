<?php if (!empty($errors)): ?>
	<div class="alert alert-danger">
		<p>Vous n'avez pas remplis le formulaire correctement</p>
		<ul>
			<?php foreach($errors as $error): ?>
				<li><?= $error; ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
<div id="user-banniere">
	<form method="post" enctype="multipart/form-data">
		<input type="file" name="file" id="fileBan" />
	</form>
	<div id="user-avatar">
		<form method="post" enctype="multipart/form-data">
		 <input type="file" name="file" id="file" />
		</form>
	</div>
</div><br>
<div id="user-name">
	<?= $_SESSION['auth']->nom_complet ?>
</div>
<div id="user-login">
	@<?= $_SESSION['auth']->login ?>
</div>
<div id="choice">
<a id="tweet-display" href="?display=tweet">Tweets</a>
	<a id="follow-display" href="?display=follow">Followers/Following</a>
</div>
<div class="tweet">
	<?php if(isset($tweets) && $tweets != false){
		$tweet->displayTweets($tweets);
	}?>
</div>
<button id="update" class="btn btn-warning">Modifier votre profil</button>
<div id="Fromulaire">
	<form method="post" id="pass" class="well col-md-12">
		<div class="form-group">
			<input class="form-control" type="text" name="login" value="<?= $_SESSION['auth']->login ?>">
		</div>
		<div class="form-group">
			<input class="form-control" type="text" name="nom_complet" value="<?= $_SESSION['auth']->nom_complet ?>">
		</div>
		<div class="form-group">
			<input class="form-control" type="email" name="email" value="<?= $_SESSION['auth']->email ?>">
		</div>
		<div class="form-group">
			<input class="form-control" type="password" name="mdp" placeholder="entrez votre nouveau mot de passe">
		</div>
		<div class="form-group">
			<input class="form-control" type="password" name="mdp_confirm" placeholder="confirmer le nouveau mot de passe">
		</div>
		<button type="submit" class="btn btn-primary" name="submit" value="1">Mettre a jour</button>
		<button type="submit" class="btn btn-danger" name="submit" value="0">Suprimer mon compte</button>
	</form>
</div>