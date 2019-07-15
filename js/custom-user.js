$(function () {
  'use strict'

  $('[data-toggle="offcanvas"]').on('click', function () {
    $('.offcanvas-collapse').toggleClass('open')
  })
});

// function AddNewField() {
//   var input = document.createElement('input');
//   var InputId = "inpt_child";
//   // var a = 0;
//   var a = document.getElementsByName('inpt_child[]').length;
//   for (b = 0; b < a; b++) {
//     b;
//   }
//   input.type = 'text';
//   input.setAttribute('name', 'inpt_child[]');
//   input.setAttribute('id', InputId + b);
//   input.setAttribute('class', 'form-control mb-1');
//   input.setAttribute('placeholder', 'Full Name');
//   var div_children = document.getElementById('div_children');
//   div_children.appendChild(input);
// }

// function RemoveField() {
//   var c = document.getElementsByName('inpt_child[]').length;
//   if ( c > 1 ) {
//     $('#div_children input:last').remove();
//     return false;
//   }
// }

// function AddRow_eb() {
//   var tablerow = document.createElement('tr');
//   var tablerowId = "NewRowEb";
//   var d = document.getElementsByClassName('new-row-eb').length;
//   for(e = 0; e < d; e++) {
//     e;
//   }

//   var educ_tbody = document.getElementById('educ_tbody');
//   tablerow.setAttribute('class', 'new-row-eb');
//   if (e>=5) {
//     tablerow.setAttribute('id', 'LimitMsg-eb');
//     educ_tbody.appendChild(tablerow);
//     var tableDisp = document.createElement('td');
//     tableDisp.setAttribute('colspan', 8);
//     tablerow.appendChild(tableDisp);
//     msgSpan = document.createElement('strong');
//     msgSpan.setAttribute('style', 'text-align:center;color:red');
//     tableDisp.appendChild(msgSpan);
//     msgSpan.append('Reached Max Rows');
//     document.getElementById('AddRow_btn_eb').setAttribute('disabled', true);
//   }
//   else {
//     tablerow.setAttribute('id', tablerowId+e);
//     educ_tbody.appendChild(tablerow);
//     for(f = 1;f <= 8; f++) {
//       var tableDisp = document.createElement('td');
//       tablerow.appendChild(tableDisp);
//       var input = document.createElement('input');
//       input.type = 'text';
//       input.setAttribute('class', 'form-control');
//       input.setAttribute('placeholder', '---');
//       tableDisp.appendChild(input);
//     }
//   }
// }

// function RemoveRow_eb() {
//   var g = document.getElementsByClassName('new-row-eb').length;
//   if (g > 0) {
//     $('#educ_tbody tr:last').remove();
//     $('#LimitMsg-eb').remove();
//     document.getElementById('AddRow_btn_eb').removeAttribute('disabled');
//     return false;
//   }
// }

// function AddRow_cse() {
//   var tablerow = document.createElement('tr');
//   var tablerowId = "NewRowCSE";
//   var d = document.getElementsByClassName('new-row-cse').length;
//   for(e = 0; e < d; e++) {
//     e;
//   }

//   var educ_tbody = document.getElementById('cse_tbody');
//   tablerow.setAttribute('class', 'new-row-cse');
//   if (e>=5) {
//     tablerow.setAttribute('id', 'LimitMsg-cse');
//     educ_tbody.appendChild(tablerow);
//     var tableDisp = document.createElement('td');
//     tableDisp.setAttribute('colspan', 6);
//     tablerow.appendChild(tableDisp);
//     msgSpan = document.createElement('strong');
//     msgSpan.setAttribute('style', 'text-align:center;color:red');
//     tableDisp.appendChild(msgSpan);
//     msgSpan.append('Reached Max Rows');
//     document.getElementById('AddRow_btn_cse').setAttribute('disabled', true);
//   }
//   else {
//     tablerow.setAttribute('id', tablerowId+e);
//     educ_tbody.appendChild(tablerow);
//     for(f = 1;f <= 6; f++) {
//       var tableDisp = document.createElement('td');
//       tablerow.appendChild(tableDisp);
//       var input = document.createElement('input');
//       input.type = 'text';
//       input.setAttribute('class', 'form-control');
//       input.setAttribute('placeholder', '---');
//       tableDisp.appendChild(input);
//     }
//   }
// }

// function RemoveRow_cse() {
//   var g = document.getElementsByClassName('new-row-cse').length;
//   if (g > 0) {
//     $('#cse_tbody tr:last').remove();
//     $('#LimitMsg-cse').remove();
//     document.getElementById('AddRow_btn_cse').removeAttribute('disabled');
//     return false;
//   }
// }

// function AddRow_we() {
//   var tablerow = document.createElement('tr');
//   var tablerowId = "NewRowWE";
//   var d = document.getElementsByClassName('new-row-we').length;
//   for(e = 0; e < d; e++) {
//     e;
//   }

//   var educ_tbody = document.getElementById('we_tbody');
//   tablerow.setAttribute('class', 'new-row-we');
//   if (e>=5) {
//     tablerow.setAttribute('id', 'LimitMsg-we');
//     educ_tbody.appendChild(tablerow);
//     var tableDisp = document.createElement('td');
//     tableDisp.setAttribute('colspan', 8);
//     tablerow.appendChild(tableDisp);
//     msgSpan = document.createElement('strong');
//     msgSpan.setAttribute('style', 'text-align:center;color:red');
//     tableDisp.appendChild(msgSpan);
//     msgSpan.append('Reached Max Rows');
//     document.getElementById('AddRow_btn_we').setAttribute('disabled', true);
//   }
//   else {
//     tablerow.setAttribute('id', tablerowId+e);
//     educ_tbody.appendChild(tablerow);
//     for(f = 1;f <= 8; f++) {
//       var tableDisp = document.createElement('td');
//       tablerow.appendChild(tableDisp);
//       var input = document.createElement('input');
//       input.type = 'text';
//       input.setAttribute('class', 'form-control');
//       input.setAttribute('placeholder', '---');
//       tableDisp.appendChild(input);
//     }
//   }
// }

// function RemoveRow_we() {
//   var g = document.getElementsByClassName('new-row-we').length;
//   if (g > 0) {
//     $('#we_tbody tr:last').remove();
//     $('#LimitMsg-we').remove();
//     document.getElementById('AddRow_btn_we').removeAttribute('disabled');
//     return false;
//   }
// }

// function AddRow_vw() {
//   var tablerow = document.createElement('tr');
//   var tablerowId = "NewRowVW";
//   var d = document.getElementsByClassName('new-row-vw').length;
//   for(e = 0; e < d; e++) {
//     e;
//   }

//   var educ_tbody = document.getElementById('vw_tbody');
//   tablerow.setAttribute('class', 'new-row-vw');
//   if (e>=5) {
//     tablerow.setAttribute('id', 'LimitMsg-vw');
//     educ_tbody.appendChild(tablerow);
//     var tableDisp = document.createElement('td');
//     tableDisp.setAttribute('colspan', 5);
//     tablerow.appendChild(tableDisp);
//     msgSpan = document.createElement('strong');
//     msgSpan.setAttribute('style', 'text-align:center;color:red');
//     tableDisp.appendChild(msgSpan);
//     msgSpan.append('Reached Max Rows');
//     document.getElementById('AddRow_btn_vw').setAttribute('disabled', true);
//   }
//   else {
//     tablerow.setAttribute('id', tablerowId+e);
//     educ_tbody.appendChild(tablerow);
//     for(f = 1;f <= 5; f++) {
//       var tableDisp = document.createElement('td');
//       tablerow.appendChild(tableDisp);
//       var input = document.createElement('input');
//       input.type = 'text';
//       input.setAttribute('class', 'form-control');
//       input.setAttribute('placeholder', '---');
//       tableDisp.appendChild(input);
//     }
//   }
// }

// function RemoveRow_vw() {
//   var g = document.getElementsByClassName('new-row-vw').length;
//   if (g > 0) {
//     $('#vw_tbody tr:last').remove();
//     $('#LimitMsg-vw').remove();
//     document.getElementById('AddRow_btn_vw').removeAttribute('disabled');
//     return false;
//   }
// }

// function AddRow_tp() {
//   var tablerow = document.createElement('tr');
//   var tablerowId = "NewRowTP";
//   var d = document.getElementsByClassName('new-row-tp').length;
//   for(e = 0; e < d; e++) {
//     e;
//   }

//   var educ_tbody = document.getElementById('tp_tbody');
//   tablerow.setAttribute('class', 'new-row-tp');
//   if (e>=5) {
//     tablerow.setAttribute('id', 'LimitMsg-tp');
//     educ_tbody.appendChild(tablerow);
//     var tableDisp = document.createElement('td');
//     tableDisp.setAttribute('colspan', 7);
//     tablerow.appendChild(tableDisp);
//     msgSpan = document.createElement('strong');
//     msgSpan.setAttribute('style', 'text-align:center;color:red');
//     tableDisp.appendChild(msgSpan);
//     msgSpan.append('Reached Max Rows');
//     document.getElementById('AddRow_btn_tp').setAttribute('disabled', true);
//   }
//   else {
//     tablerow.setAttribute('id', tablerowId+e);
//     educ_tbody.appendChild(tablerow);
//     for(f = 1;f <= 7; f++) {
//       var tableDisp = document.createElement('td');
//       tablerow.appendChild(tableDisp);
//       var input = document.createElement('input');
//       input.type = 'text';
//       input.setAttribute('class', 'form-control');
//       input.setAttribute('placeholder', '---');
//       tableDisp.appendChild(input);
//     }
//   }
// }

// function RemoveRow_tp() {
//   var g = document.getElementsByClassName('new-row-tp').length;
//   if (g > 0) {
//     $('#tp_tbody tr:last').remove();
//     $('#LimitMsg-tp').remove();
//     document.getElementById('AddRow_btn_tp').removeAttribute('disabled');
//     return false;
//   }
// }

// function AddRow_oi() {
//   var tablerow = document.createElement('tr');
//   var tablerowId = "NewRowOI";
//   var d = document.getElementsByClassName('new-row-oi').length;
//   for(e = 0; e < d; e++) {
//     e;
//   }

//   var educ_tbody = document.getElementById('oi_tbody');
//   tablerow.setAttribute('class', 'new-row-oi');
//   if (e>=5) {
//     tablerow.setAttribute('id', 'LimitMsg-oi');
//     educ_tbody.appendChild(tablerow);
//     var tableDisp = document.createElement('td');
//     tableDisp.setAttribute('colspan', 3);
//     tablerow.appendChild(tableDisp);
//     msgSpan = document.createElement('strong');
//     msgSpan.setAttribute('style', 'text-align:center;color:red');
//     tableDisp.appendChild(msgSpan);
//     msgSpan.append('Reached Max Rows');
//     document.getElementById('AddRow_btn_oi').setAttribute('disabled', true);
//   }
//   else {
//     tablerow.setAttribute('id', tablerowId+e);
//     educ_tbody.appendChild(tablerow);
//     for(f = 1;f <= 3; f++) {
//       var tableDisp = document.createElement('td');
//       tablerow.appendChild(tableDisp);
//       var input = document.createElement('input');
//       input.type = 'text';
//       input.setAttribute('class', 'form-control');
//       input.setAttribute('placeholder', '---');
//       tableDisp.appendChild(input);
//     }
//   }
// }

// function RemoveRow_oi() {
//   var g = document.getElementsByClassName('new-row-oi').length;
//   if (g > 0) {
//     $('#oi_tbody tr:last').remove();
//     $('#LimitMsg-oi').remove();
//     document.getElementById('AddRow_btn_oi').removeAttribute('disabled');
//     return false;
//   }
// }