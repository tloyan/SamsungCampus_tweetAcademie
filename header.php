<?php
error_reporting(E_ALL);
require_once './class/bootstrap.php';
require_once 'class/tweet_class.php';
require_once './class/comment_class.php';
require_once './class/follow_class.php';
Session::getInstance();
$db = App::getDatabase();
$search = new SearchTag();
$tweet = Tweet::Instance();
$uri = $_SERVER['REQUEST_URI'];
$commentObj = Comment::Instance();
$follow = Follow::Instance();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Tweet Academy</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/reset_css.css"/>
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
	<link rel="stylesheet" type="text/css" href="css/tweet.css"/>
	<link rel="stylesheet" type="text/css" href="css/users.css"/>
	<link rel="stylesheet" type="text/css" href="css/tchat.css"/>
	<script
	src="http://code.jquery.com/jquery-3.1.1.js"
	integrity="sha256-16cdPddA6VdVInumRGo6IbivbERE8p7CQR3HzTBuELA="
	crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script type="text/javascript" src="scripts/update.js"></script>
	<script src="scripts/ajax.js" type="text/javascript"></script>
</head>
<body>
	<div id="header">
		<div id="container-header">
			<a href="./index.php"><img alt="logo-poulet" src="./images/logo.png"/></a>
			<a id="home" href="index.php">Home</a>
			<a id="about" href="https://about.twitter.com/company">About</a>
			<form method="get" id="searchHashtag">
				<input type="text" name="hashtag" placeholder="Saisir votre recherche"/>
				<input type="submit" value="valider">
			</form>
			<?php if(!isset($_SESSION['auth'])): ?>
				<a id="login" href="login.php">Login</a>
				<a id="subscribe" href="register.php">Subscribe</a>
			<?php else: ?>
				<a id="logout" href="logout.php">Logout</a>
				<a id="Profil" href="account.php">Profil</a>
			<?php endif; ?>
			<div class="fill"></div>
		</div>
	</div>
	<?php	
	if(!empty($_GET) && isset($_GET['hashtag'])) {
		$tags = $search->hashtagSearch($db);
		if($tags){
			$tags = $tweet->getHashtagsLinks($tags);
			$tags = $tweet->getUsersLinks($tags);
			$tweet->displayTweets($tags);
		}
		exit;
	}
	?>