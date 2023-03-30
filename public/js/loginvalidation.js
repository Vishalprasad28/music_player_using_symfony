$(document).ready(function(){
  $('form').submit(function(e){
    $(".error-box p").html("");
    e.preventDefault();
    $('#submit').html('<img src="public//assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    var email = $('#email').val();
    var pwd = $('#pwd').val();
    var submit = $('#submit').val();
    $.post("/postLogin",{
      email: email,
      pwd: pwd,
      login: submit },
      function(data,status){
        if(status == 'success') {
          $('#submit').attr('disabled',false);
          $('#submit').html('Log in');
        }  
        if (data == 'success') {
          $(".error-box p").html("");
          window.location.href = '/mainpage';
        }
        else if (data == 'Wrong Password') {
          data = data + '<br>' + '<a href="/forgot-password" class="forgot-pwd">Forgot password?</a>';
          $(".error-box p").html(data);
        }
        else if (data == 'User Not Found') {
          data = data + '<br>' + '<a href="/register" class="forgot-pwd">New User ?</a>';
          $(".error-box p").html(data);
        }
        else {
          $(".error-box p").html(data);
        }
      });
  });
});