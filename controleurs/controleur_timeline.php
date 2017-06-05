<?php
if(isset($_SESSION['auth'])){
	$tweets = $tweet->getAllTweets($_SESSION['auth']->id);
	if(isset($tweets)){
		$tweets = $tweet->getHashtagsLinks($tweets);
		$tweets = $tweet->getUsersLinks($tweets);
	}
	if(!empty($_POST) && isset($_POST['contenu'])){
		$check = $tweet->checkTweet($_POST);
		if($check === true){
			if(!empty($_FILES['image']['name'])){
				$checkImage = $tweet->checkImage($_FILES);
				if($checkImage === true){
					$tweet->sendTweetWithPicture($_POST, $_FILES, $_SESSION['auth']->id);
				}
				elseif($checkImage !== false){
					echo $checkImage;
				}
			}
			else{
				$tweet->sendTweet($_POST, $_SESSION['auth']->id);
			}
		}
		else{
			echo $check;
		}
	}
	if(isset($_POST['comment-value'])){
		$check = $commentObj->sendComment($_POST['comment-value'], $_POST['id_tweet'], $_SESSION['auth']->id);
		if(is_string($check)){
			echo $check;
		}
	}
	if(isset($_POST['retweet-value'])){
		$tweet->retweet($_GET['retweet'], $_SESSION['auth']->id);
	}
	if(isset($_GET['delete']) && !isset($_GET['confirmed'])){
		include './vues/deleteTweet.php';
	}
	if(isset($_GET['confirmed'])){
		$delete = $tweet->deleteTweet($_GET['delete']);
		if ($delete == true){
			include './vues/deleteConfirmed.php';
		}
	}
	if(isset($_GET['comment'])){
		$comment = $_GET['comment'];
	}
	if(isset($_GET['retweet'])){
		include './vues/retweet.php';
	}
	include 'vues/timeline.php';
}