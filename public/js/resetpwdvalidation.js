$(document).ready(function(){
  $('form').submit(function(e){
    $(".error-box p").html("");
    e.preventDefault();
    $('#submit').html('<img src="public//assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    var submit = $('#submit').val();
    var pwd = $('#pwd').val();
    var confpwd = $('#conf-pwd').val();
    $.post("controller/resetpwdcontroller.php",{
      pwd: pwd,
      reset: submit,
      confPwd: confpwd
    },
      function(data,status){
      if (status == 'success') {
        $('#submit').html('Reset Password');

      }
      if (data != "success"){
        $('input').attr('disabled',false);
        $('button').attr('disabled',false);
        $('#submit').html('Reset Password');
        $(".error-box p").html(data);
      }
      else {
        $('input').attr('disabled',true);
        $('button').attr('disabled',true);
        $('#submit').html('Reset Password');
        $(".error-box p").html(data);
      }
        });
      });
  });