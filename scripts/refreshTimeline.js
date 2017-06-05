var refresh = setInterval(function(){
	var check = checkRefresh();
	if(check == false){
		$.ajax({
			url: '/my_twitter/ajax/refreshTimeline.php',
			success: function(output){
				$('#timeline').html(output);
			},
			error: function(error){
			}
		})
	}
}, 2000);

function checkRefresh(){
	var form = document.getElementById('comment-form');
	if(form != null){
		return true;
	}
	else{
		return false;
	}
}