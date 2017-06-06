$(function() {
 $('#login').focus();

 function empty_valid() {

 }

 function not_valid(field, helptext) {
  var group = $('div#'+field+'-group');
  var help = $('span#'+field+'-help');
  var icon = $('span#'+field+'-icon');
  group.removeClass('has-success').addClass('has-error');
  help.html(helptext);
  icon.removeClass('glyphicon-ok').addClass('glyphicon-remove');
 }

 function valid(field, helptext) {
  var group = $('div#'+field+'-group');
  var help = $('span#'+field+'-help');
  var icon = $('span#'+field+'-icon');
  group.removeClass('has-error').addClass('has-success');
  help.html(helptext);
  icon.removeClass('glyphicon-remove').addClass('glyphicon-ok');
 }

 function validate_field(field, value) {
  if (field == 'password2' && value != $('#password1').val()) {
   not_valid('password2', 'Пароли не совпадают');
   return;
  }

  $.ajax({
   url: '/index/validate',
   type: "POST",
   dataType: "JSON",
   data: {
    field: field,
    value: value
   }
  })
   .done(function(response) {
    if (response.result) {
     valid(field, '');
    } else if (response.error) {
     not_valid(field, response.error);
    }
   })
   .fail(function() {

   });
 }

 function validate_all() {
  $.ajax({
   url: '/index/register',
   type: "POST",
   dataType: "JSON",
   data: {

   }
  })
   .done(function(response) {

   })
   .fail(function() {

   });
 }

 $('.form-control').blur(function() {
  var id = $(this).attr('id');
  var value = $(this).val();
  if (value == '') { return; }
  validate_field(id, value);
 });

 $('form#register-form').submit(function() {
  validate_all();
  return false;
 });
});