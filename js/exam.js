//check for Navigation Timing API support
// if (window.performance) {
//   console.info("window.performance works fine on this browser");
// }
// check if the user reloads the page
// if (performance.navigation.type == 1) {
// 	if (confirm("The exam will be forced to end. Do you still want to reload?")==true) {
// 		alert("Exam Terminated.");
// 		s_NT(0,0);
// 		timer_end("Times Up!");
		
// 	} else {
// 		alert("Please continue answering.");
// 	}
// } else {
// 	console.info( "Good luck");
// }

document.cookie = ' counter=;expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
var nt_s = document.getElementById('et_seconds').value;
var nt_m = document.getElementById('et_minutes').value;
var clock = document.getElementById('timeleft');
var TimerMsg = 'Times Up!';
timer_c();

function timer_c() {
	var i_s = ((nt_s <= 0 || nt_s > 59) ? 0 : nt_s);
	var i_m = ((nt_m <= 0) ? 0 : nt_m);
	var d_sec = '';
	var d_min = '';
	var disp = '';
	var cnsl = '';
	var startTimer = setInterval(function() {
		var d_sec = ((i_s < 10) ? '0' : '') + i_s.toString();
		var d_min = ((i_m < 10) ? '0' : '') + i_m.toString();

		if (i_s == 59 && i_m == -1) {
			disp = TimerMsg;
			cnsl = TimerMsg+ '|' + i_m + ':' + i_s;
			s_NT(0, 0);
			clearInterval(startTimer);
			timer_end(disp);
		} else {
			disp = d_min + ':' + d_sec;
			cnsl = d_min + ':' + d_sec + '|' + i_m + ':' + i_s;
			s_NT(i_s, i_m);
		}

		if (i_s > -1) {
			if (i_s == 0) {
				i_s = 60;
				i_m--;
			}
		}
		i_s--;
		console.log(cnsl);
		clock.innerHTML = disp;
	}, 1000);
	count_timer_c();
}

function s_NT(s, m) {
	document.cookie = "et_sec="+s+"; path=/";
	document.cookie = "et_min="+m+"; path=/";
}


function timer_end(msg) {
	console.log(msg);
	document.getElementById('timeleft').innerHTML = msg;
	document.getElementById('timeleft').setAttribute('style', 'color: red;');
	window.alert(msg);
	document.getElementById('exam_frm').setAttribute('class', 'hidden');
	document.getElementById('exam_end').classList.remove('hidden');
}

function count_timer_c() {
    var a = getCookie('counter');
    if (a == "") {
        var b = 0;
    } else {
        var b = getCookie('counter');
    }
    b++;
    console.log(b);
    if (b > 1) {
    	b--;
    	console.log('asd');
    	timer_forceEnd();
    }
    document.cookie = "counter="+b+"; path=/";
}

function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function timer_forceEnd() {
	for (var i = 1; i < 99999; i++) {
		window.clearInterval(i);
	}
	s_NT(0, 0);
	var msg = "YOU'RE CHEATING!";
	timer_end(msg);
}

