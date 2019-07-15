var min_year = new Date().getFullYear();
max_year = min_year - 10;
// yearselector = document.getElementById('YearSelector');
yearselector = document.getElementsByClassName('YearSelector');

for (var i = 0; i < yearselector.length; i++) {
	for (var a = min_year; a > max_year; a--){
		var opt = document.createElement('option');
		opt.value = a;
		opt.innerHTML = a;
		yearselector[i].appendChild(opt);
	}
}

// monthselector = document.getElementById('MonthSelector');
monthselector = document.getElementsByClassName('MonthSelector');
var monthArray = new Array();
monthArray[0] = "January";
monthArray[1] = "February";
monthArray[2] = "March";
monthArray[3] = "April";
monthArray[4] = "May";
monthArray[5] = "June";
monthArray[6] = "July";
monthArray[7] = "August";
monthArray[8] = "September";
monthArray[9] = "October";
monthArray[10] = "November";
monthArray[11] = "December";

for (var i = 0; i < monthselector.length; i++) {
	for (var b = 0; b <= 11; b++) {
		var opt = document.createElement('option');
		opt.value = b+1;
		opt.innerHTML = monthArray[b];
		if (b == new Date().getMonth()) {
			opt.setAttribute('selected', 'true');
		}
		monthselector[i].appendChild(opt);
	}
}

