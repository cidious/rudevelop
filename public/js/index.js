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

 function validate_field(field) {

 }

 function validate_all() {

 }

 $('.form-control').blur(function() {
  var id = $(this).attr('id');
  var value = $(this).val();
  if (value == '') { return; }

  //console.log(id+' blur '+value);
 });

 $('form#register-form').submit(function() {
  //console.log('submit');
  return false;
 });
});