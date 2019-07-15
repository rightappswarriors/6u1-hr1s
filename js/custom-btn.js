var btn_spin = document.getElementsByClassName('btn-spin');
for (var a = 0; a < btn_spin.length; a++) {
	btn_spin[a].onclick = function() {
		$('#spinning-icon').remove();
		var icon = document.createElement('i')
		icon.setAttribute('class', 'fa fa-spinner fa-spin');
		icon.setAttribute('style', 'margin-left:5px;');
		icon.id = 'spinning-icon';
		this.appendChild(icon);
		setTimeout(RemoveSpinningIcon, 10000); //Note: 1000 = 1 sec
	};
}

function RemoveSpinningIcon() {
	$('#spinning-icon').remove();
}

var btn_file = document.getElementsByClassName('btn-file');
for(var i = 0; i < btn_file.length; i++) {
	btn_file[i].onclick = function() {
		var target_container = document.getElementById(this.getAttribute('data-target'));
		for(var i = 0; i < target_container.childNodes.length; i++) {
			if (target_container.childNodes[i].className == "form-control-file file-array") {
				var target_child = target_container.childNodes[i];
				var input = document.createElement('input');
				input.type = 'file';
				input.name = target_child.name;
				input.className = 'form-control-file mt-2';
				target_container.appendChild(input);
			}
		}
	}
}