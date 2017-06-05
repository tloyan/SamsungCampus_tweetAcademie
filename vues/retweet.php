<form method="POST" id="retweet">
	<?php 
	foreach($tweets as $value){
		if($value->id_tweet == $_GET['retweet']){
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
			echo '<div class="fill"></div>';
			echo '</div>';
		}
	}
	?>
	<input name="retweet-value" type="texte" placeholder="Retweet"/>
</form>