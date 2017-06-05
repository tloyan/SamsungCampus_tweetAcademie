<?php include 'header.php';
if(isset($_SESSION['auth'])):
include 'tchat.php';
endif;
switch ($uri) {
	case '/my_twitter/login.php':
		include 'controleurs/controleur_login.php';
		break;
	
	case '/my_twitter/register.php':
		include 'controleurs/controleur_register.php';
		break;

	case '/my_twitter/logout.php':
		include 'controleurs/controleur_logout.php';
		break;

	case '/my_twitter/account.php':
		include 'controleurs/controleur_account.php';
		break;

	case '/my_twitter/timeline.php':
		include 'controleurs/controleur_timeline.php';
		break;

	case '/my_twitter/account.php?display=tweet':
		include 'controleurs/controleur_account.php';
		break;

	case '/my_twitter/account.php?display=follow':
		include 'controleurs/controleur_account.php';
		break;

	case '/my_twitter/index.php':
		if(!empty($_SESSION)){
			include 'controleurs/controleur_timeline.php';
		}
		elseif(empty($_SESSION) && $uri == '/my_twitter/index.php'){
			include 'vues/accueil.php';
		}
		break;

	default:
		if(strstr($uri, '/my_twitter/index.php?comment=') != false){
			$comment = true;
			include 'controleurs/controleur_timeline.php';
		}
		elseif(strstr($uri, '/my_twitter/index.php?delete=') != false){
			include 'controleurs/controleur_timeline.php';
		}
		elseif(strstr($uri, '/my_twitter/index.php?retweet=') != false){
			include 'controleurs/controleur_timeline.php';
		}
		else{
			include './vues/404.php';
		}
		break;
}