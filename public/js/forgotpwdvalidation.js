$(document).ready(function(){
  $('form').submit(function(e){
    e.preventDefault();
    $(".error-box p").html("");
    $('#submit').html('<img src="public/assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    var email = $('#email').val();
    var verify = $('#submit').val();
    $.post("controller/forgotpwdcontroller.php",{
      email: email,
      verify: verify },
      function(data,status){
        if (status == 'success') {
          $('#submit').html('Verify');
        }
        if(data == "success") {
          $(".error-box p").html('We have sent you a reset password link on your email');
          $('input').attr('disabled',true);
          $('#submit').attr('disabled',true);
        }
        else {
          $('input').attr('disabled',false);
          $('#submit').attr('disabled',false);
          $(".error-box p").html(data);
        }
        });
      });
  });