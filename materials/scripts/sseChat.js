if(typeof(EventSource) !== 'undefined') {
	let source = new EventSource('/messages/sse/' + window.location.href.split('/')[4]);
	window.onbeforeunload = function() {
		source.close();
	}
	source.onmessage = function(event) {
		let json = JSON.parse(event.data);
		for(value of json) {
			addMessage(value);
		}
	};
	source.onerror = function(e) {
		console.log('Connection error, retrying');
	};
} else {
	console.log('Your browser does not support server-sent events');
}

function addMessage(data) {
	let chat = document.getElementById('chat');
	let div = document.createElement('div');
	div.innerHTML = '<b>' + data.sender + '</b> : ' + data.date + '<br>' + data.message;
	chat.appendChild(div);
}