$(document).ready(function(){
  $('#email').keyup(function() {
    var validemail = new RegExp('^[a-z]+[\.0-9a-z]*[+1]*[@]{1}[a-z]+[.]{1}([c][o][m]|[o][r][g])+$')
    var email = $(this).val();
    if(email == ""){
      $('#email-error').addClass("error-msg");
      $('#email-error').html("Required");
    }
    else if(!validemail.test(email)){
      $('#email-error').addClass("error-msg");
      $('#email-error').html("Invalid Email Formate");
    }
    else {
      $('#email-error').removeClass("error-msg");
      $('#email-error').html("&nbsp;");
    }
  });
  $('#pwd').keyup(function() {

    var pwd = $(this).val();
    const hasUpperCase = /[A-Z]/.test(pwd);
    const hasLowerCase = /[a-z]/.test(pwd);
    const hasNumbers = /\d/.test(pwd);
    const hasSpecial = /\W/.test(pwd);

    let errors = '&nbsp;';

    if (pwd.length < 8) {
      // errors.push('min length 8.')
      errors = errors.concat('min length 8 required, ');
    }
    if (!hasUpperCase) {
      // errors.push('Password must contain at least one uppercase letter.')
      errors = errors.concat('at lease one uppercase letter, ');

    }
    if (!hasLowerCase) {
      // errors.push('Password must have at least one lowercase letter.');
      errors = errors.concat('at lease one lowercase letter, ');
    }
    if (!hasNumbers) {
      // errors.push('Password must have at least one number.');
      errors = errors.concat('at lease one number, ');
    }
    if (!hasSpecial) {
      // errors.push('Password must have at least one special character.');
      errors = errors.concat('at lease one special character, ');
    }
    $('#pwd-error').addClass("error-msg");
    $('#pwd-error').html(errors);
    errors = '&nbsp;';
  });

  function namevalidation(data) {
    var name = data;
    var validname = new RegExp("^[a-zA-Z-' ]*$");
    if (name == "") {
      return "Required";
    }
    else if (!validname.test(name)) {
      return "Invalid Formate";
    }
    else {
      return "&nbsp;";
    }
  }

  $('#fname').keyup(function(){
    var fname = $(this).val();
    $('#fname-error').addClass("error-msg");
    $('#fname-error').html(namevalidation(fname));
  });
  $('#lname').keyup(function(){
    var fname = $(this).val();
    $('#lname-error').addClass("error-msg");
    $('#lname-error').html(namevalidation(fname));
  });
  $('#conf-pwd').keyup(function(){
    var pwd = $('#pwd').val();
    var confpwd = $(this).val();
    if(pwd != confpwd) {
      $('#submit').attr("disabled",true);
      $('#conf-pwd-error').addClass("error-msg");
      $('#conf-pwd-error').html("Password Doesn't match");
    }
    else {
      $('#submit').attr("disabled",false);
      $('#conf-pwd-error').html("&nbsp;");
    }
  });
  $('#phone').keyup(function(){
    var phone = $('#phone').val();
    var validPhone = new RegExp('^([+]{1}[9]{1}[1]{1}[6-9]{1}[0-9]{9})$');
    if(phone == "") {
      $('#phone-error').addClass("error-msg");
      $('#phone-error').html("Required");
    }
    else if(!validPhone.test(phone)) {
      $('#phone-error').addClass("error-msg");
      $('#phone-error').html("Invalid Formate");
    }
    else {
      $('#phone-error').html("&nbsp;");
    }
  });
  $("#fname,#lname").keyup(function () {
    var fname = $("#fname").val();
    var lname = $("#lname").val();
    $("#fullname").val("");
    $("#fullname").val(fname + " " + lname);
});
  $('.eye-close-icon').click(function(){
    $(this).addClass('hide-icon');
    var parent = $(this).parent();
    $(parent).children('input').attr('type','text');
    $(parent).children('.eye-open-icon').removeClass('hide-icon');
  });
  $('.eye-open-icon').click(function(){
    $(this).addClass('hide-icon');
    var parent = $(this).parent();
    $(parent).children('input').attr('type','password');
    $(parent).children('.eye-close-icon').removeClass('hide-icon');
  });
});

