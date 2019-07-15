// function prevPage(prevform, currentform, setActive, removeActive) {
//   prevform.setAttribute('style', 'display: block;');
//   currentform.setAttribute('style', 'display: none;');
//   setActive.setAttribute('class', 'breadcrumb-item active');
//   removeActive.setAttribute('class', 'breadcrumb-item');
// }

// function nextPage(currentform, nextform, setActive, removeActive) {
//   currentform.setAttribute('style', 'display: none;');
//   nextform.setAttribute('style', 'display: block;');
//   setActive.setAttribute('class', 'breadcrumb-item active');
//   removeActive.setAttribute('class', 'breadcrumb-item');
// }

// function prevBTN() {
//   var form_icon = document.getElementsByClassName('breadcrumb-item');
//   var form_icon_active = document.getElementsByClassName('breadcrumb-item active')[0].id;
//   var icon = 0;
//   var form_body = document.getElementsByClassName('form-body');
//   var form_body_active = document.getElementsByClassName('form-body active')[0].id;
//   var body = 0;
//   for(i=0;i<form_icon.length;i++) {
//     form_icon[i].classList.remove('active');
//     if (form_icon[i].id == form_icon_active) {
//       icon = i;
//     }
//   }
//   for(a=0;a<form_body.length;a++) {
//     form_body[a].classList.add('hidden');
//     form_body[a].classList.remove('active');
//     if (form_body[a].id == form_body_active) {
//       body = a;
//     }
//   }
//   if (icon == 0) {
//     icon = form_icon.length;
//   }
//   if (body == 0) {
//     body = form_body.length;
//   }
//   form_icon[icon-1].classList.add('active');
//   form_body[body-1].classList.remove('hidden');
//   form_body[body-1].classList.add('active');
//   document.getElementById('header-text').innerHTML = form_body[body-1].getAttribute('title');
//   document.getElementById('header-icon').innerHTML = form_icon[icon-1].innerHTML;
//   disable_BTN(body-1);
// }

// function nextBTN() {
//   var form_icon = document.getElementsByClassName('breadcrumb-item');
//   var form_icon_active = document.getElementsByClassName('breadcrumb-item active')[0].id;
//   var icon = 0;
//   var form_body = document.getElementsByClassName('form-body');
//   var form_body_active = document.getElementsByClassName('form-body active')[0].id;
//   var body = 0;
//   for(i=0;i<form_icon.length;i++) {
//     form_icon[i].classList.remove('active');
//     if (form_icon[i].id == form_icon_active) {
//       icon = i;
//     }
//   }
//   for(a=0;a<form_body.length;a++) {
//     form_body[a].classList.add('hidden');
//     form_body[a].classList.remove('active');
//     if (form_body[a].id == form_body_active) {
//       body = a;
//     }
//   }
//   if (icon == form_icon.length-1) {
//     icon = -1;
//   }
//   if (body == form_body.length-1) {
//     body = -1;
//   }
//   form_icon[icon+1].classList.add('active');
//   form_body[body+1].classList.remove('hidden');
//   form_body[body+1].classList.add('active');
//   document.getElementById('header-text').innerHTML = form_body[body+1].getAttribute('title');
//   document.getElementById('header-icon').innerHTML = form_icon[icon+1].innerHTML;
//   disable_BTN(body+1);
// }

// function chckFields() {
  
// }

// function disable_BTN(btn_val) {
//   var btn_prev = document.getElementById('btn-prev');
//   var btn_next = document.getElementById('btn-next');
//   var form_icon = document.getElementsByClassName('breadcrumb-item');
//   if (btn_val == 0) {
//     btn_prev.setAttribute('disabled', 'true');
//   }
//   else {
//     btn_prev.removeAttribute('disabled'); 
//   }

//   if (btn_val == form_icon.length-1) {
//     btn_next.setAttribute('disabled', 'true');
//   }
//   else {
//     btn_next.removeAttribute('disabled');
//   }
// }

// var bc_item = document.getElementsByClassName('breadcrumb-item');
// var frm_body = document.getElementsByClassName('form-body');
// for(i=0; i<bc_item.length;i++) {
//   bc_item[i].addEventListener('click', function() {
//     for(i=0; i<bc_item.length;i++) {
//       bc_item[i].classList.remove('active');
//     }
//     this.classList.add('active');
//     for(i=0; i<frm_body.length;i++) {
//       frm_body[i].setAttribute('class', 'form-body hidden');
//       if (frm_body[i].id == this.id+"_frm") {
//         frm_body[i].setAttribute('class', 'form-body active');
//       }
//     }
//   });
// }

function Form_Submit() {
  var rc = document.getElementById('review_card');
  var sc = document.getElementById('send_card');
  var btn_prev = document.getElementById('btn-prev');
  var btn_next = document.getElementById('btn-next');
  rc.classList.remove('show');
  rc.classList.add('hidden');
  sc.classList.add('show');
  // btn_prev.setAttribute('disabled', 'true');
  // btn_next.setAttribute('disabled', 'true');
}