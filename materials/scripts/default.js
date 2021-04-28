document.addEventListener('DOMContentLoaded', function() {
	let pageForms = document.querySelectorAll('form');
	let buttons = document.querySelectorAll('.removeDialog');
	if(pageForms.length) {
		for(let f of pageForms) f.addEventListener('submit', sendFormData);
	}
	if(buttons.length) {
		for(let btn of buttons) btn.addEventListener('click', RemoveDialog);
	}
});

function RemoveDialog() {
	var formData = new FormData();
	formData.append('id', event.target.id);
	sendData(formData, window.location.href);
}

function sendFormData() {
	event.preventDefault();
	var formData = new FormData(event.target);
	sendData(formData, window.location.href);
}

async function sendData(formData, path) {
	fetch(path, {
		method: 'POST',
		body: formData
	})
		.then((res) => res.json())
		.then((data) => resultActions(data))
		.catch((error) => console.log(error))
}

function resultActions(result) {	
	if(!Object.keys(result).length === 0) {
		return;
	}
	if(result.url) {
		window.location.href = '//' + result.url;
	}
	if(result.message) {
		alert(result.message);
	}
	if(result.methods) {
		result.methods.forEach(method => 
			{
				if(result.data && result.data[method]) {
					window[method](result.data[method]);
				} else {
					window[method]();
				}
			});
	}
	if(result.console) {
		console.log(result.console);
	}
}

function removeDialog(id) {
	document.querySelector("[id='" + id + "']").parentElement.remove();
}

function resetForm() {
	document.querySelector('form').reset();
}