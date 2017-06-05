<form action="timeline.php" enctype="multipart/form-data" id="newTweet" method="POST">
	<!-- Ajouter l'avatar ici -->
	<textarea id="newTweet-content" oninput="getInput()" name="contenu" placeholder="What's happening ?"></textarea>
	<input id="newTweet-image" type="file" name="image"/>
	<input type="submit" value="" id="newTweet-submit"/> 
	<div class="fill"></div>
</form>
<div id="timeline">
	<?php
	// var_dump($tweets);
	if($tweets){
		if(isset($comment)){
			$tweet->displayTweets($tweets, $comment);
		}
		else{
			$tweet->displayTweets($tweets);
		}
	}?>
</div>
<script type="text/javascript" src="./scripts/refreshTimeline.js"></script>