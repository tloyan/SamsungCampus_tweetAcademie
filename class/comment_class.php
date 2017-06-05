<?php
class Comment{

	private $pdo;

	public static function Instance(){
		static $inst = null;
		if ($inst === null){
			$inst = new Comment();
		}
		return $inst;
	}

	public function __construct(){
        $pdo = new PDO('mysql:dbname=twitter;host=localhost','root','');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->pdo = $pdo;
    }

    public function sendComment($value, $id_tweet, $id_user){
    	$check = $this->checkComment($value);
    	if($check !== true){
    		return $check;
    	}
    	$query = $this->pdo->prepare('INSERT INTO commentaires (commentaire, id_tweet, id_user) VALUES(:value, :id_tweet, :id_user)');
    	$query->execute(['value' => $value, 'id_tweet' => $id_tweet, 'id_user' => $id_user]);
    }

    public function checkComment($value){
    	if(strlen($value) < 3 || strlen($value) > 140){
    		return '<p class="warning">Your comment must contain between 3 and 140 characters.</p>';
    	}
    	else{
    		return true;
    	}
    }

    public function getComments($id_tweet){
    	$comments = array();
    	$query = $this->pdo->prepare('SELECT users.nom_complet, commentaire, DATE_FORMAT(date_commentaire, \'%d/%m/%Y at %H:%i:%s\') date_commentaire FROM commentaires LEFT JOIN users ON commentaires.id_user = users.id WHERE id_tweet=:id_tweet');
    	$query->execute(['id_tweet' => $id_tweet]);
    	while($row = $query->fetch()){
    		$comments[] = $row;
    	}
    	if(!empty($comments)){
    		$this->displayComments($comments);
    	}
    }

    public function displayComments($comments){
    	foreach($comments as $comment){
    		echo '<div class="comment">';
    		echo '<div class="comment-header">';
    		echo '<p class="comment-autor">' . $comment->nom_complet . '</p>';
    		echo '<p class="comment-timestamp">' . $comment->date_commentaire . '</p>';
    		echo '</div>';
    		echo '<div class="fill"></div>';
    		echo '<div class="comment-body">';
    		echo '<p class="comment-content">' . $comment->commentaire . '</p>';
    		echo '</div>';
    		echo '</div>';
    	}
    }
}