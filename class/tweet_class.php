<?php
final class Tweet {
	
	private $pdo;
	private $comment;

	public static function Instance(){
		static $inst = null;
		if ($inst === null){
			$inst = new Tweet();
		}
		return $inst;
	}

	private function __construct(){
		$pdo = new PDO('mysql:dbname=twitter;host=localhost','root','');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$this->pdo = $pdo;
		$comment = Comment::Instance();
		$this->comment = $comment;
	}

	public function getAllTweets($id_user){
		$query = $this->pdo->prepare('SELECT follows FROM users WHERE id=:id_user');
		$query->execute(['id_user' => $id_user]);
		$follows = $query->fetch()->follows;
		if($follows != null){
			if(strpos($follows, ';')){
				$follows = explode(';', $follows);
			} 
			else{
				$follows = array($follows);
			}
		}
		else{
			$follows = array($_SESSION['auth']->id);
		}
		$myTweet = array();
		$query = $this->pdo->prepare('SELECT id_user, id_tweet, DATE_FORMAT(date_tweet, \'%d/%m/%Y at %H:%i:%s\') date_tweet, tweet, hashtags, url_image FROM tweet WHERE id_user=:id_user ORDER BY date_tweet DESC');
		$query->execute(['id_user' => $id_user]);
		while($row = $query->fetch()){
			$myTweet[] = $row;
		}
		$tweet = array();
		foreach ($follows as $user){
			$query = $this->pdo->prepare('SELECT id_user, id_tweet, DATE_FORMAT(date_tweet, \'%d/%m/%Y at %H:%i:%s\') date_tweet, tweet, hashtags, url_image FROM tweet WHERE id_user=:id_user ORDER BY date_tweet DESC');
			$query->execute	(['id_user' => $user]);
			While($row = $query->fetch()){
				$tweet[] = $row;
			}
			if(isset($tweet)){
				foreach($tweet as $value){
					$query = $this->pdo->prepare('SELECT nom_complet FROM users WHERE id=:id_user');
					$query->execute(['id_user' => $user]);
					$value->login = $query->fetch()->nom_complet;
				}
			}
		}
		foreach($myTweet as $value){
			$value->login = $_SESSION['auth']->nom_complet;
		}
		if(isset($tweet)){
			foreach($myTweet as $value){
				array_push($tweet, $value);
			}
			usort($tweet, array('tweet', 'cmp'));
			$tweet = array_reverse($tweet);
			return $tweet;
		}
	}

	public function checkTweet($data){
		if(strlen($data['contenu']) < 3 || strlen($data['contenu']) > 140){
			return '<p class="warning">Your tweet must contain between 2 and 140 characters.</p>';
		}
		return true;
	}

	public function cmp($a, $b){
		return strcmp($a->date_tweet, $b->date_tweet);
	}

	public function checkImage($data){
		if(empty($_FILES)){
			return false;
		}
		if($data['image']['size'] > 1000000){
			return '<p class="warning">Your image must weight under 1Mo</p>';
		}
		$type = explode('/', $data['image']['type'])[0];
		$extension = explode('/', $data['image']['type'])[1];
		if($type != 'image'){
			return '<p class="warning">This type of file is not yet supported</p>';
		}
		return true;
	}

	public function sendTweetWithPicture($form, $files, $id){
		$hashtags = $this->getHashtags($form['contenu']);
		$extension = explode('/', $files['image']['type'])[1];
		$path = './images/tweets/' . substr(md5(rand()), 0, 7) . '.' . $extension;
		while(in_array($path, scandir('./images/tweets'))){
			$path = './images/tweets/' . substr(md5(rand()), 0, 7) . '.' . $extension;
		}
		move_uploaded_file($files['image']['tmp_name'], $path);
		$query = $this->pdo->prepare('INSERT INTO tweet (id_user, tweet, url_image, hashtags) VALUES (:id_user, :tweet, :url_image, :hashtags)');
		try{
			$query->execute(['id_user' => $id,
				'tweet' => $form['contenu'],
				'url_image' => $path,
				'hashtags' => $hashtags]);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function sendTweet($form, $id){
		$hashtags = $this->getHashtags($form['contenu']);
		$query = $this->pdo->prepare('INSERT INTO tweet (id_user, tweet, hashtags) VALUES (:id_user, :tweet, :hashtags)');
		try{
			$query->execute(['id_user' => $id,
				'tweet' => $form['contenu'],
				'hashtags' => $hashtags]);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function getHashtags($string){
		$hashtags = '';
		$words = explode(' ', $string);
		foreach($words as $word){
			if($word[0] == '#'){
				$hashtags .= $word;
			}
		}
		return $hashtags;
	}

	public function getHashtagsLinks($tweets){
		foreach($tweets as &$oneTweet){
			$words = explode(' ', $oneTweet->tweet);
			foreach ($words as &$word){
				if(isset($word[0])){
					if($word[0] == '#'){
						$word = '<a class="hashtags" href="?hashtag=' . substr($word, 1) . '">' . $word . '</a>';
					}
				}
			}
			$oneTweet->tweet = implode(' ', $words);
		}
		return $tweets;
	}

	public function deleteTweet($id_tweet){
		$query = $this->pdo->prepare('DELETE FROM tweet WHERE id_tweet=:id_tweet');
		$query->execute(['id_tweet' => $id_tweet]);
		if($query == true){
			return true;
		}
	}

	public function getUsersLinks($tweets){
		foreach($tweets as &$oneTweet){
			$words = explode(' ', $oneTweet->tweet);
			foreach($words as &$word){
				if(isset($word[0])){
					if($word[0] == '@'){
						$word = '<a class="user-link" href="?user=' . substr($word, 1) . '">' . $word . '</a>';
					}
				}
			}
			$oneTweet->tweet = implode(' ', $words);
		}
		return $tweets;
	}

	public function displayTweets($tweet, $comment = null){
		foreach ($tweet as $value){
			echo '<div class="tweet">';
			echo '<div class="tweet-head">';
			echo '<p class="tweet-author">' . $value->login . '</p>';
			echo '<p class="tweet-timestamp">' . $value->date_tweet . '</p>';
			echo '<div class="fill"></div>';
			echo '</div>';
			echo '<p class="tweet-contenu">' . $value->tweet . '</p>';
			echo '<div class="fill"></div>';
			if(!empty($value->url_image)){
				echo '<a href="' . $value->url_image . '"><img alt="image du tweet" class="tweet-image" src="' . $value->url_image . '"/></a>';
			}
			echo '<a class="tweet-comment" href="?comment=' . $value->id_tweet . '">Answer</a>';
			echo '<a class="tweet-comment" href="?retweet=' . $value->id_tweet . '"><img alt="image-retweet" src="/my_twitter/images/retweet.png"/></a>';
			if($value->id_user == $_SESSION['auth']->id){
				echo '<a class="tweet-delete" href="?delete=' . $value->id_tweet . '">Delete tweet</a>';
			}
			if(isset($comment) && $value->id_tweet == $comment){
				include './vues/comment.php';
			}
			$comments = $this->comment->getComments($value->id_tweet);
			echo '<div class="fill"></div>';
			echo '</div>';
		}
	}

	public function getMyTweets($id, $pseudo){
		$query = $this->pdo->prepare('SELECT id_user, id_tweet, DATE_FORMAT(date_tweet, \'%d/%m/%Y at %H:%i:%s\') date_tweet, tweet, hashtags, url_image FROM tweet WHERE id_tweet=id_tweet ORDER BY date_tweet DESC');
		$query->execute(['id_user' => $id]);
		while($row = $query->fetch()){
			$tweets[] = $row;
		}
		if(isset($tweets) && $tweets != null){
			foreach($tweets as $tweet){
				$tweet->login = $pseudo;
			}
			return $tweets;
		}
		else{
			return false;
		}
	}

	public function reTweet($id_tweet, $id_user){
		$query = $this->pdo->prepare('SELECT retweeteurs FROM tweet WHERE id_tweet=:id_tweet');
		$query->execute(['id_tweet' => $id_tweet]);
		$retweet = $query->fetch();
		var_dump($retweet);
		if(!is_null($retweet)){
			if(!strpos($retweet->retweeteurs, $id_user)){
				$retweeteurs = $retweet->retweeteurs . ';' . $id_user;
			}
			else{
				$retweeteurs = $retweet->retweeteurs;
			}
		}
		else{
			$retweeteurs = $id_user;
		}
		$query = $this->pdo->prepare('UPDATE tweet SET retweeteurs=:retweeteurs');
		$query->execute(['retweeteurs' => $retweeteurs]);
	}
}