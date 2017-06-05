<?php
include '../class/tweet_class.php';
require_once '../class/bootstrap.php';
require_once '../class/comment_class.php';
Session::getInstance();
$tweet = tweet::Instance();
if(!isset($_GET['comment'])){
	if(isset($_SESSION['auth'])){
		$tweets = $tweet->getAllTweets($_SESSION['auth']->id);
		if($tweets){
			$tweets = $tweet->getHashtagsLinks($tweets);
			$tweets = $tweet->getUsersLinks($tweets);
		}
		if($tweets){
			if(isset($comment)){
				$tweet->displayTweets($tweets, $comment);
			}
			else{
				$tweet->displayTweets($tweets);
			}
		}
	}
}?>