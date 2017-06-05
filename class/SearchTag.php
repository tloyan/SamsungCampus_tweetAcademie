<?php
final class SearchTag{

    public function __construct(){
        $pdo = new PDO('mysql:dbname=twitter;host=localhost','root','');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->pdo = $pdo;
    }

    public function hashtagSearch($db){
        extract($_GET);
        if(!empty($hashtag) AND isset($hashtag)){
            $tags = htmlspecialchars($hashtag);
            $tag = $db->query('SELECT id_user, id_tweet, DATE_FORMAT(date_tweet, \'%d/%m/%Y at %H:%i:%s\') date_tweet, tweet, hashtags, url_image FROM tweet WHERE hashtags LIKE "%'.$tags.'%"' . 'ORDER BY date_tweet DESC')->fetchAll();
            foreach($tag as $value){
                //REMPLACER PAR L'ID SESSION DES QUE POSSIBLE
                if($value->id_user != $_SESSION['auth']->id){
                    $value->hashtags = explode('#', $value->hashtags);
                    $query = $this->pdo->prepare('SELECT login FROM users WHERE id=:id_user');
                    $query->execute(['id_user' => $_SESSION['auth']->id]);
                    $value->login = $query->fetch()->login;
                }
                else{
                    $value->login = $_SESSION['auth']->login;
                }
            }
        }
        if(isset($tag)){
            return $tag;
        }
    } 
}
?>