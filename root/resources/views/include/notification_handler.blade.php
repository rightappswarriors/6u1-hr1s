@php
  if(isset($_GET['ntf_id']) && isset($_GET['uid'])) {
    $uid = $_GET['uid'];
    $xntf_id = $_GET['ntf_id'];
    Notification_N::Toggle_Notification($uid, $xntf_id, true);
  }
@endphp

<script>
	var notif_reload_seconds = 10;

	@isset(Account::CURRENT()->uid)
	function notif_find() { 
	    $.ajax({
	    	type: "post",
	    	url: notif_yoarel,
	    	data: {"uid":'{{Account::CURRENT()->uid}}'} ,
	    	success: function(response) {
	    		generate_notif(response);

	        	let noti_count = response[2];

        		$('#notif_count').html( (noti_count > 10) ? "10+" : noti_count );
	    	}
	    });
	}
	@endisset

	function generate_notif(response) {
		if(response[2] > 0) {
			$('#notif_count').removeAttr('hidden');
		} else {
			$('#notif_count').attr('hidden');
		}

	    var div = document.getElementById('main_parent_div');
	        var h6 = document.createElement('h6');
	            h6.setAttribute('class', 'dropdown-header');
	            h6.innerHTML ='Notifications:';
	        var divider = document.createElement('div');
	            divider.setAttribute('class', 'dropdown-divider');

	        while(div.firstChild) {
	          div.removeChild(div.firstChild);
	        }

	    div.appendChild(h6);
	    div.appendChild(divider);

	    if(response[1].length > 0) {
	      for(i=0; i<response[1].length; i++) {
	        let data = response[1][i];
	        let data1 = response[0][i];
	        var a = document.createElement('a');
	            a.setAttribute('class', 'dropdown-item');
	            if(!data1.seen) a.setAttribute('style', 'background: rgb(227,232,240)');
	            a.setAttribute('href', notif_base_yoarel+data.url_readable+'?ntf_id='+data1.ntf_id+'&uid='+data1.uid);
	            var span = document.createElement('div');
	                span.setAttribute('class', 'text-black');
	                var b = document.createElement('b');
	                    b.innerHTML = data.ntf_subj;
	                    if(!data1.seen)
	                      b.innerHTML = b.innerHTML + "<span class='float-right text-danger'>NEW</span>";
	                span.appendChild(b)
	            var span1 = document.createElement('span');
	                span1.setAttribute('class', 'small float-right text-muted');
	                // span1.innerHTML = data.time_readable;
	                span1.innerHTML = moment(data.ntf_date).fromNow();
	            var span2 = document.createElement('span');
	                span2.setAttribute('class', 'dropdown-message small');
	                span2.innerHTML = data.ntf_cont;

	            a.appendChild(span);
	            a.appendChild(span1);
	            a.appendChild(span2);
	        div.appendChild(a);
	      } 

	    } else {
	      var a = document.createElement('a');
	          a.setAttribute('class', 'dropdown-item');
	          a.setAttribute('href', '#');
	          var span = document.createElement('div');
	              span.setAttribute('class', 'text-info');
	              var b = document.createElement('b');
	                  b.innerHTML = 'No new notification';
	              span.appendChild(b);
	          var span1 = document.createElement('span');
	              span1.setAttribute('class', 'dropdown-message small');
	              span1.innerHTML = 'You are all up to date.';
	          a.appendChild(span);
	          a.appendChild(span1);
	      div.appendChild(a);
	    }

	    var div1 = document.createElement('div');
	        div1.setAttribute('class', 'dropdown-divider');
	    var a1 = document.createElement('a');
	        a1.setAttribute('class', 'dropdown-item small');
	        a1.setAttribute('href', '#');
	        a1.innerHTML = 'View all alerts';
	    div.appendChild(div1);
	    div.appendChild(a1);
  	}
</script>

<script>
    setInterval(function() {
        notif_find();
    }, notif_reload_seconds * 500);
</script>