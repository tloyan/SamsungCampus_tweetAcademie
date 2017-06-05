<h1>S'inscrire</h1>

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

<form action="#" method="POST">
	<div class="form-group row col-md-12" >
		<label>Pseudo</label>
		<input type="text" name="login" class="form-control"/>
	</div>
	<div class="form-group row col-md-12">
		<label>nom complet</label>
		<input type="text" name="nom_complet" class="form-control"/>
	</div>
	<div class="form-group row col-md-12">
		<label>email</label>
		<input type="text" name="email" class="form-control"/>
	</div>
	<div class="form-group row col-md-12">
		<label>Mot de passe</label>
		<input type="password" name="mdp" class="form-control"/>
	</div>
	<div class="form-group row col-md-12">
		<label>Confirmez votre mot de passe</label>
		<input type="password" name="mdp_confirm" class="form-control"/>
	</div>

	<button type="submit" class="btn btn-primary">M'inscrire</button>
</form>