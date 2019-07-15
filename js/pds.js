$(document).ready(function() {
  $( "#birthday" ).datepicker(date_option);
  for (var i = 0; i < $(".datepicker").length; i++) {
    $( ".datepicker" ).datepicker(date_option)[i];
  }
  $( "#scndry_poa_frm" ).datepicker(date_option);
  $( "#scndry_poa_to" ).datepicker(date_option);
  $( "#clgy_poa_frm" ).datepicker(date_option);
  $( "#clgy_poa_to" ).datepicker(date_option);
  $( "#grdstds_poa_frm" ).datepicker(date_option);
  $( "#grdstds_poa_to" ).datepicker(date_option);
  $( "#grdstds_poa_to" ).datepicker(date_option);
  $( "#grdstds_poa_to" ).datepicker(date_option);
  $( "#cse_date" ).datepicker(date_option);
  $( "#cse_license_date" ).datepicker(date_option);
  $( "#we_date_frm" ).datepicker(date_option);
  $( "#we_date_to" ).datepicker(date_option)
  $( "#vw_date_frm" ).datepicker(date_option)
  $( "#vw_date_to" ).datepicker(date_option)
  $( "#tp_date_frm" ).datepicker(date_option)
  $( "#tp_date_to" ).datepicker(date_option)
});

function Fill_pi() {
  document.getElementsByName('pi_lastname')[0].value = "myLastName";
  document.getElementsByName('pi_middlename')[0].value = "myMiddleName";
  document.getElementsByName('pi_firstname')[0].value = "myFirstName";
  document.getElementsByName('pi_namextension')[0].value = "myNameXtension";
  document.getElementById('radio_sex0').checked = true;
  document.getElementsByName('pi_placeofbirth')[0].value = "myBirthplace";
  document.getElementsByName('pi_birthday')[0].value = "09/11/2018";
  document.getElementsByName('pi_bloodtype')[0].value = "O";
  document.getElementsByName('pi_height')[0].value = 1.7;
  document.getElementsByName('pi_weight')[0].value = 50;
  document.getElementsByName('pi_email')[0].value = "cleotes.janpaolo0723@gmail.com";
  document.getElementsByName('pi_ctznshp_sel')[0].value = "Filipino";
  document.getElementsByName('pi_cvlstts_sel')[0].value = "Single";
  document.getElementsByName('pi_gsis')[0].value = "123";
  document.getElementsByName('pi_pagibig')[0].value = "123";
  document.getElementsByName('pi_philhealth')[0].value = "123";
  document.getElementsByName('pi_sss')[0].value = "123";
  document.getElementsByName('pi_tin')[0].value = "123";
  document.getElementsByName('pi_agencyemployeeno')[0].value = "123";
  document.getElementsByName('pi_telno')[0].value = "111-222-3333";
  document.getElementsByName('pi_mobno')[0].value = "09771033922";
}

function Fill_ripa() {
  document.getElementsByName('ri_hblno')[0].value = "ri_hblno";
  document.getElementsByName('ri_strt')[0].value = "ri_strt";
  document.getElementsByName('ri_subdiv')[0].value = "ri_subdiv";
  document.getElementsByName('ri_brngy')[0].value = "ri_brngy";
  document.getElementsByName('ri_ctymn')[0].value = "ri_ctymn";
  document.getElementsByName('ri_prvnc')[0].value = "ri_prvnc";
  document.getElementsByName('ri_zipcode')[0].value = "ri_zipcode";
  document.getElementsByName('pa_hblno')[0].value = "pa_hblno";
  document.getElementsByName('pa_strt')[0].value = "pa_strt";
  document.getElementsByName('pa_subdiv')[0].value = "pa_subdiv";
  document.getElementsByName('pa_brngy')[0].value = "pa_brngy";
  document.getElementsByName('pa_citymun')[0].value = "pa_citymun";
  document.getElementsByName('pa_prvnc')[0].value = "pa_prvnc";
  document.getElementsByName('pa_zipcode')[0].value = "pa_zipcode";
}

function Fill_fb() {
  document.getElementsByName('fa_spouse_lname')[0].value = "spouseLastname";
  document.getElementsByName('fa_spouse_mname')[0].value = "spouseMiddlename";
  document.getElementsByName('fa_spouse_fname')[0].value = "spouseFirstname";
  document.getElementsByName('fa_spouse_namextension')[0].value = "spouseNameXtension";
  document.getElementsByName('fa_spouse_occptn')[0].value = "spouseOccupation";
  document.getElementsByName('fa_spouse_mplyr')[0].value = "spouseEmployerName";
  document.getElementsByName('fa_spouse_bsnsadd')[0].value = "spouseEmployerAddress";
  document.getElementsByName('fa_spouse_bsnsadd')[0].value = "spouseEmployerAddress";
  document.getElementsByName('fa_spouse_telno')[0].value = "111-222-3333";
  document.getElementsByName('fa_father_lname')[0].value = "fatherlname";
  document.getElementsByName('fa_father_fname')[0].value = "fatherfname";
  document.getElementsByName('fa_father_mname')[0].value = "fathermname";
  document.getElementsByName('fa_father_namextension')[0].value = "fatherxname";
  document.getElementsByName('fa_mother_lname')[0].value = "motherlname";
  document.getElementsByName('fa_mother_fname')[0].value = "motherfname";
  document.getElementsByName('fa_mother_mname')[0].value = "mothermname";
  document.getElementsByName('fa_mother_namextension')[0].value = "motherxname";
  document.getElementsByName('fa_chldrn[]')[0].value = "childnumbah1";
}

function Fill_eb() {
  document.getElementsByName('elm_schlname')[0].value = "elm_schlname";
  document.getElementsByName('elm_bedc')[0].value = "elm_bedc";
  document.getElementsByName('elm_poa_frm')[0].value = "09/11/2018";
  document.getElementsByName('elm_poa_to')[0].value = "09/11/2018";
  document.getElementsByName('elm_hghstlevel')[0].value = "elm_hghstlevel";
  document.getElementsByName('elm_yeargrad')[0].value = "elm_yeargrad";
  document.getElementsByName('elm_ahr')[0].value = "elm_ahr";
  document.getElementsByName('scndry_schlname')[0].value = "scndry_schlname";
  document.getElementsByName('scndry_bedc')[0].value = "scndry_bedc";
  document.getElementsByName('scndry_poa_frm')[0].value = "09/11/2018";
  document.getElementsByName('scndry_poa_to')[0].value = "09/11/2018";
  document.getElementsByName('scndry_hghstlevel')[0].value = "scndry_hghstlevel";
  document.getElementsByName('scndry_yeargrad')[0].value = "scndry_yeargrad";
  document.getElementsByName('scndry_ahr')[0].value = "scndry_ahr";
  document.getElementsByName('clgy_schlname')[0].value = "clgy_schlname";
  document.getElementsByName('clgy_bedc')[0].value = "clgy_bedc";
  document.getElementsByName('clgy_poa_frm')[0].value = "09/11/2018";
  document.getElementsByName('clgy_poa_to')[0].value = "09/11/2018";
  document.getElementsByName('clgy_hghstlevel')[0].value = "clgy_hghstlevel";
  document.getElementsByName('clgy_yeargrad')[0].value = "clgy_yeargrad";
  document.getElementsByName('clgy_ahr')[0].value = "clgy_ahr";
  document.getElementsByName('grdstds_schlname')[0].value = "grdstds_schlname";
  document.getElementsByName('grdstds_bedc')[0].value = "grdstds_bedc";
  document.getElementsByName('grdstds_poa_frm')[0].value = "09/11/2018";
  document.getElementsByName('grdstds_poa_to')[0].value = "09/11/2018";
  document.getElementsByName('grdstds_hghstlevel')[0].value = "grdstds_hghstlevel";
  document.getElementsByName('grdstds_yeargrad')[0].value = "grdstds_yeargrad";
  document.getElementsByName('grdstds_ahr')[0].value = "grdstds_ahr";
}

function Fill_cse() {
  document.getElementsByName('cse_row1')[0].value = "cse_row1";
  document.getElementsByName('cse_rating')[0].value = "cse_rating";
  document.getElementsByName('cse_date')[0].value = "09/11/2018";
  document.getElementsByName('cse_place')[0].value = "cse_place";
  document.getElementsByName('cse_license_num')[0].value = "cse_license_num";
  document.getElementsByName('cse_license_date')[0].value = "09/11/2018";
}

function Fill_we() {
  document.getElementsByName('we_date_frm')[0].value = "09/11/2018";
  document.getElementsByName('we_date_to')[0].value = "09/11/2018";
  document.getElementsByName('we_position')[0].value = "we_position";
  document.getElementsByName('we_daoc')[0].value = "we_daoc";
  document.getElementsByName('we_mnthlyslry')[0].value = "we_mnthlyslry";
  document.getElementsByName('we_sjp')[0].value = "we_sjp";
  document.getElementsByName('we_status')[0].value = "we_status";
  document.getElementsByName('we_gv')[0].value = "we_gv";
}

function Fill_vw() {
  document.getElementsByName('vw_orgname')[0].value = "vw_orgname";
  document.getElementsByName('vw_date_frm')[0].value = "09/11/2018";
  document.getElementsByName('vw_date_to')[0].value = "09/11/2018";
  document.getElementsByName('vw_numofhrs')[0].value = "100";
  document.getElementsByName('vw_position')[0].value = "vw_position";
}

function Fill_tp() {
  document.getElementsByName('tp_title')[0].value = "tp_title";
  document.getElementsByName('tp_date_frm')[0].value = "09/11/2018";
  document.getElementsByName('tp_date_to')[0].value = "09/11/2018";
  document.getElementsByName('tp_numofhrs')[0].value = "100";
  document.getElementsByName('tp_typeofID')[0].value = "tp_typeofID";
  document.getElementsByName('tp_sponsor')[0].value = "tp_sponsor";
}

function Fill_oi() {
  document.getElementsByName('oi_skills')[0].value = "oi_skills";
  document.getElementsByName('oi_nonacadreg')[0].value = "oi_nonacadreg";
  document.getElementsByName('oi_org')[0].value = "oi_org";
}

function Fill_ALL() {
  Fill_pi();
  Fill_ripa();
  Fill_fb();
  Fill_eb();
  Fill_cse();
  Fill_we();
  Fill_vw();
  Fill_tp();
  Fill_oi();
}

function Specify_Fld(select,input) {
	var select_fld = document.getElementById(select);
	var input_fld = document.getElementById(input);
	if (select_fld.value == "Others") {
		input_fld.classList.remove('hidden');
	}
	else {
		input_fld.classList.add('hidden');
	}
}

function AddNewField() {
  var input = document.createElement('input');
  var InputId = "inpt_child";
  // var a = 0;
  var a = document.getElementsByName('fa_chldrn[]').length;
  for (b = 0; b < a; b++) {
    b;
  }
  input.type = 'text';
  input.setAttribute('name', 'fa_chldrn[]');
  input.setAttribute('id', InputId + b);
  input.setAttribute('class', 'form-control mb-1');
  input.setAttribute('placeholder', 'Full Name');
  var div_children = document.getElementById('div_children');
  div_children.appendChild(input);
}

function RemoveField(a) {
  var c = document.getElementsByName('fa_chldrn[]').length;
  if ( c > a ) {
    $('#div_children input:last').remove();
    return false;
  }
}

function AddRow_eb() {
  var tablerow = document.createElement('tr');
  var tablerowId = "NewRowEb";
  var d = document.getElementsByClassName('new-row-eb').length;
  for(e = 0; e < d; e++) {
    e;
  }

  var educ_tbody = document.getElementById('educ_tbody');
  tablerow.setAttribute('class', 'new-row-eb');
  if (e>=5) {
    tablerow.setAttribute('id', 'LimitMsg-eb');
    educ_tbody.appendChild(tablerow);
    var tableDisp = document.createElement('td');
    tableDisp.setAttribute('colspan', 8);
    tablerow.appendChild(tableDisp);
    msgSpan = document.createElement('strong');
    msgSpan.setAttribute('style', 'text-align:center;color:red');
    tableDisp.appendChild(msgSpan);
    msgSpan.append('Reached Max Rows');
    document.getElementById('AddRow_btn_eb').setAttribute('disabled', true);
  }
  else {
    tablerow.setAttribute('id', tablerowId+e);
    educ_tbody.appendChild(tablerow);
    for(f = 1;f <= 8; f++) {
      var tableDisp = document.createElement('td');
      tablerow.appendChild(tableDisp);
      var input = document.createElement('input');
      input.type = 'text';
      input.setAttribute('name', 'NewRowEB'+f+'[]');
      input.setAttribute('class', 'form-control');
      input.setAttribute('placeholder', '---');
      tableDisp.appendChild(input);
    }
  }
}

function RemoveRow_eb(a) {
  var g = document.getElementsByClassName('new-row-eb').length;
  if (g > a) {
    $('#educ_tbody tr:last').remove();
    $('#LimitMsg-eb').remove();
    document.getElementById('AddRow_btn_eb').removeAttribute('disabled');
    return false;
  }
}

function AddRow_cse() {
  var tablerow = document.createElement('tr');
  var tablerowId = "NewRowCSE";
  var d = document.getElementsByClassName('new-row-cse').length;
  for(e = 0; e < d; e++) {
    e;
  }

  var educ_tbody = document.getElementById('cse_tbody');
  tablerow.setAttribute('class', 'new-row-cse');
  if (e>=5) {
    tablerow.setAttribute('id', 'LimitMsg-cse');
    educ_tbody.appendChild(tablerow);
    var tableDisp = document.createElement('td');
    tableDisp.setAttribute('colspan', 6);
    tablerow.appendChild(tableDisp);
    msgSpan = document.createElement('strong');
    msgSpan.setAttribute('style', 'text-align:center;color:red');
    tableDisp.appendChild(msgSpan);
    msgSpan.append('Reached Max Rows');
    document.getElementById('AddRow_btn_cse').setAttribute('disabled', true);
  }
  else {
    tablerow.setAttribute('id', tablerowId+e);
    educ_tbody.appendChild(tablerow);
    for(f = 1;f <= 6; f++) {
      var tableDisp = document.createElement('td');
      tablerow.appendChild(tableDisp);
      var input = document.createElement('input');
      input.type = 'text';
      input.setAttribute('name', 'NewRowCSE'+f+'[]');
      input.setAttribute('class', 'form-control');
      input.setAttribute('placeholder', '---');
      tableDisp.appendChild(input);
    }
  }
}

function RemoveRow_cse(a) {
  var g = document.getElementsByClassName('new-row-cse').length;
  if (g > a) {
    $('#cse_tbody tr:last').remove();
    $('#LimitMsg-cse').remove();
    document.getElementById('AddRow_btn_cse').removeAttribute('disabled');
    return false;
  }
}

function AddRow_we() {
  var tablerow = document.createElement('tr');
  var tablerowId = "NewRowWE";
  var d = document.getElementsByClassName('new-row-we').length;
  for(e = 0; e < d; e++) {
    e;
  }

  var educ_tbody = document.getElementById('we_tbody');
  tablerow.setAttribute('class', 'new-row-we');
  if (e>=5) {
    tablerow.setAttribute('id', 'LimitMsg-we');
    educ_tbody.appendChild(tablerow);
    var tableDisp = document.createElement('td');
    tableDisp.setAttribute('colspan', 8);
    tablerow.appendChild(tableDisp);
    msgSpan = document.createElement('strong');
    msgSpan.setAttribute('style', 'text-align:center;color:red');
    tableDisp.appendChild(msgSpan);
    msgSpan.append('Reached Max Rows');
    document.getElementById('AddRow_btn_we').setAttribute('disabled', true);
  }
  else {
    tablerow.setAttribute('id', tablerowId+e);
    educ_tbody.appendChild(tablerow);
    for(f = 1;f <= 8; f++) {
      var tableDisp = document.createElement('td');
      tablerow.appendChild(tableDisp);
      var input = document.createElement('input');
      input.type = 'text';
      input.setAttribute('name', 'NewRowWE'+f+'[]');
      input.setAttribute('class', 'form-control');
      input.setAttribute('placeholder', '---');
      tableDisp.appendChild(input);
    }
  }
}

function RemoveRow_we(a) {
  var g = document.getElementsByClassName('new-row-we').length;
  if (g > a) {
    $('#we_tbody tr:last').remove();
    $('#LimitMsg-we').remove();
    document.getElementById('AddRow_btn_we').removeAttribute('disabled');
    return false;
  }
}

function AddRow_vw() {
  var tablerow = document.createElement('tr');
  var tablerowId = "NewRowVW";
  var d = document.getElementsByClassName('new-row-vw').length;
  for(e = 0; e < d; e++) {
    e;
  }

  var educ_tbody = document.getElementById('vw_tbody');
  tablerow.setAttribute('class', 'new-row-vw');
  if (e>=5) {
    tablerow.setAttribute('id', 'LimitMsg-vw');
    educ_tbody.appendChild(tablerow);
    var tableDisp = document.createElement('td');
    tableDisp.setAttribute('colspan', 5);
    tablerow.appendChild(tableDisp);
    msgSpan = document.createElement('strong');
    msgSpan.setAttribute('style', 'text-align:center;color:red');
    tableDisp.appendChild(msgSpan);
    msgSpan.append('Reached Max Rows');
    document.getElementById('AddRow_btn_vw').setAttribute('disabled', true);
  }
  else {
    tablerow.setAttribute('id', tablerowId+e);
    educ_tbody.appendChild(tablerow);
    for(f = 1;f <= 5; f++) {
      var tableDisp = document.createElement('td');
      tablerow.appendChild(tableDisp);
      var input = document.createElement('input');
      input.type = 'text';
      input.setAttribute('name', 'NewRowVW'+f+'[]');
      input.setAttribute('class', 'form-control');
      input.setAttribute('placeholder', '---');
      tableDisp.appendChild(input);
    }
  }
}

function RemoveRow_vw(a) {
  var g = document.getElementsByClassName('new-row-vw').length;
  if (g > a) {
    $('#vw_tbody tr:last').remove();
    $('#LimitMsg-vw').remove();
    document.getElementById('AddRow_btn_vw').removeAttribute('disabled');
    return false;
  }
}

function AddRow_tp() {
  var tablerow = document.createElement('tr');
  var tablerowId = "NewRowTP";
  var d = document.getElementsByClassName('new-row-tp').length;
  for(e = 0; e < d; e++) {
    e;
  }

  var educ_tbody = document.getElementById('tp_tbody');
  tablerow.setAttribute('class', 'new-row-tp');
  if (e>=5) {
    tablerow.setAttribute('id', 'LimitMsg-tp');
    educ_tbody.appendChild(tablerow);
    var tableDisp = document.createElement('td');
    tableDisp.setAttribute('colspan', 6);
    tablerow.appendChild(tableDisp);
    msgSpan = document.createElement('strong');
    msgSpan.setAttribute('style', 'text-align:center;color:red');
    tableDisp.appendChild(msgSpan);
    msgSpan.append('Reached Max Rows');
    document.getElementById('AddRow_btn_tp').setAttribute('disabled', true);
  }
  else {
    tablerow.setAttribute('id', tablerowId+e);
    educ_tbody.appendChild(tablerow);
    for(f = 1;f <= 6; f++) {
      var tableDisp = document.createElement('td');
      tablerow.appendChild(tableDisp);
      var input = document.createElement('input');
      input.type = 'text';
      input.setAttribute('name', 'NewRowTP'+f+'[]');
      input.setAttribute('class', 'form-control');
      input.setAttribute('placeholder', '---');
      tableDisp.appendChild(input);
    }
  }
}

function RemoveRow_tp(a) {
  var g = document.getElementsByClassName('new-row-tp').length;
  if (g > a) {
    $('#tp_tbody tr:last').remove();
    $('#LimitMsg-tp').remove();
    document.getElementById('AddRow_btn_tp').removeAttribute('disabled');
    return false;
  }
}

function AddRow_oi1() {
  var tablerow = document.createElement('tr');
  var tablerowId = "NewRowOI1";
  var d = document.getElementsByClassName('new-row-oi1').length;
  for(e = 0; e < d; e++) {
    e;
  }
  var tbody = document.getElementById('oi_tbody1');
  tablerow.setAttribute('class', 'new-row-oi1');
  tbody.appendChild(tablerow);
  var tableDisp = document.createElement('td');
  tablerow.appendChild(tableDisp);
  var input = document.createElement('input');
  input.type = 'text';
  input.setAttribute('name', 'NewRowOI1[]');
  input.setAttribute('class', 'form-control');
  input.setAttribute('placeholder', '---');
  tableDisp.appendChild(input);
}

function RemoveRow_oi1(a) {
  var g = document.getElementsByClassName('new-row-oi1').length;
  if (g > a) {
    $('#oi_tbody1 tr:last').remove();
    return false;
  }
}

function AddRow_oi2() {
  var tablerow = document.createElement('tr');
  var tablerowId = "NewRowOI2";
  var d = document.getElementsByClassName('new-row-oi2').length;
  for(e = 0; e < d; e++) {
    e;
  }
  var tbody = document.getElementById('oi_tbody2');
  tablerow.setAttribute('class', 'new-row-oi2');
  tbody.appendChild(tablerow);
  var tableDisp = document.createElement('td');
  tablerow.appendChild(tableDisp);
  var input = document.createElement('input');
  input.type = 'text';
  input.setAttribute('name', 'NewRowOI2[]');
  input.setAttribute('class', 'form-control');
  input.setAttribute('placeholder', '---');
  tableDisp.appendChild(input);
}

function RemoveRow_oi2(a) {
  var g = document.getElementsByClassName('new-row-oi2').length;
  if (g > a) {
    $('#oi_tbody2 tr:last').remove();
    return false;
  }
}

function AddRow_oi3() {
  var tablerow = document.createElement('tr');
  var tablerowId = "NewRowOI3";
  var d = document.getElementsByClassName('new-row-oi3').length;
  for(e = 0; e < d; e++) {
    e;
  }
  var tbody = document.getElementById('oi_tbody3');
  tablerow.setAttribute('class', 'new-row-oi3');
  tbody.appendChild(tablerow);
  var tableDisp = document.createElement('td');
  tablerow.appendChild(tableDisp);
  var input = document.createElement('input');
  input.type = 'text';
  input.setAttribute('name', 'NewRowOI3[]');
  input.setAttribute('class', 'form-control');
  input.setAttribute('placeholder', '---');
  tableDisp.appendChild(input);
}

function RemoveRow_oi3(a) {
  var g = document.getElementsByClassName('new-row-oi3').length;
  if (g > a) {
    $('#oi_tbody3 tr:last').remove();
    return false;
  }
}

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#ImgFrame')
      .attr('src', e.target.result)
      .width(133)
      .height(171);
    };

    reader.readAsDataURL(input.files[0]);
  }
}