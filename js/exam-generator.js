function NextQstn(qstn_num) {
	var qstn_card = document.getElementById('qstnCard_'+qstn_num);
	var all_qstnCard = document.getElementsByName('qstnCard');
	for(var a=0; a<all_qstnCard.length; a++) {
		all_qstnCard[a].classList.add('hidden');
	}
	qstn_card.classList.remove('hidden');
}

function PrevQstn(qstn_num) {
	var qstn_card = document.getElementById('qstnCard_'+qstn_num);
	var all_qstnCard = document.getElementsByName('qstnCard');
	for(var a=0; a<all_qstnCard.length; a++) {
		all_qstnCard[a].classList.add('hidden');
	}
	qstn_card.classList.remove('hidden');
}

function SelectAns(qstn_num, chc_lttr) {
	var selected_input = document.getElementById('answer_'+qstn_num+''+chc_lttr);
	var selected_label = document.getElementById('label_'+qstn_num+''+chc_lttr);
	qstn = document.getElementsByName('choiceSet'+qstn_num);
	// console.log(qstn);
	for(var a=0; a<qstn.length; a++) {
		qstn[a].classList.remove('selected');
	}
	if(selected_input.checked == true) {
		selected_label.classList.add('selected');
	}
}