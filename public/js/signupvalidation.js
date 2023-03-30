$(document).ready(function(){
  $('form').submit(function(e){
    e.preventDefault();
    $(".error-box p").html("");
    $('#submit').html('<img src="public/assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    var fname = $('#fname').val();
    var lname = $('#lname').val();
    var email = $('#email').val();
    var uname = $('#uname').val();
    var pwd = $('#pwd').val();
    var confPwd = $('#conf-pwd').val();
    var contact = $('#phone').val();
    var submit = $('#submit').val();
    var interest = "";
    if ($("input[name=pop]").is(':checked')) {
      interest = interest + 'pop,';
    }
    if ($("input[name=hiphop]").is(':checked')) {
      interest = interest + 'hiphop,';
    }
    if ($("input[name=romantic]").is(':checked')) {
      interest = interest + 'romantic,';
    }
    if ($("input[name=dancing]").is(':checked')) {
      interest = interest + 'dancing,';
    }
    $.post("/postSignup",{
      fname: fname,
      lname: lname,
      email: email,
      pwd: pwd,
      phone: contact,
      interest: interest,
      uname: uname,
      confPwd: confPwd,
      signup: submit },
      function(data,status){
        if (status == 'success') {
          $('#submit').attr('disabled',false);
          $('#submit').html('Sign Up');
        }
        if (data == 'success') {
          $(".error-box p").html("");
          window.location.href = '/mainpage';
        }
        else {
          $(".error-box p").html(data);
        }
    });
  });
});