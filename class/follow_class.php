<?php
class Follow{

	private $pdo;

	public static function Instance(){
		static $inst = null;
		if ($inst === null){
			$inst = new Follow();
		}
		return $inst;
	}

	public function __construct(){
        $pdo = new PDO('mysql:dbname=twitter;host=localhost','root','');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->pdo = $pdo;
    }

    public function getFollowers($id_user){
    	$query = $this->pdo->prepare('SELECT following FROM users WHERE id=:id_user');
    	$query->execute(['id_user' => $id_user]);
    	while($row = $query->fetch()){
    		$followers[] = $row;
    	}
    	foreach($followers as $users){
    		// $query - $this->pdo->prepare('SELECT ');
    	}
    }
}