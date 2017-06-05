var timer;
function search_contact(val) {
	$.ajax({
		type: 'post',
		url: '/my_twitter/ajax/searchContact.php',
		data: {
		contact:val,
		},
		success: function (response) {
		document.getElementById("contact").innerHTML=response; 
		}
	});	
}
function discution(val) {
	clearInterval(timer);
	timer = setInterval(discution, 500);
	$.ajax({
		type: 'post',
		url: '/my_twitter/ajax/discution.php',
		data: {
		discution:val,
		},
		success: function (response) {
		document.getElementById("message").innerHTML=response; 
		}
	});	
}
function insertMsg(val) {
	$.ajax({
		type: 'post',
		url: '/my_twitter/ajax/insertMsg.php',
		data: {
		insMsg:val,
		},
		success: function (response) {
		document.getElementById("form").innerHTML=response;
		}
	});	
}
function deletMsg(val) {
	$.ajax({
		type: 'post',
		url: '/my_twitter/ajax/deletMsg.php',
		data: {
		delMsg:val,
		}
	});		
}

function display() {
	var left = document.getElementById('left').style.display;
	var right = document.getElementById('right').style.display;

	if (left == 'none' || right == 'none') {
		document.getElementById('left').style.display = 'block';
		document.getElementById('right').style.display = 'block';
	} else {
		document.getElementById('left').style.display = 'none';
		document.getElementById('right').style.display = 'none';
	}
}