$(document).ready(function() {
  $('#loginForm').submit(function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    loginValidation(formData);
  });
  
  $('#signUpForm').submit(function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    signUpValidation(formData);
  });

  $('#forgotPwd-form').submit(function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    forgotPwd(formData);
  });
  $('#Reset-Form').submit(function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    resetPwd(formData);
  });
  
  // All Function Here

  function convertToArray(e) {
    var array = {};
    e.forEach(element => {
      array[element['name']] = element['value'];
    });
    return array;
  }

  function showMessage(btn, message) {
    $('#submit').attr('disabled',false);
    $('#submit').html(btn);
    $(".error-box p").html(message);
  }
  function loginValidation(e) {
    $(".error-box p").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    let array = convertToArray(e);
    $.post("/postLogin",
    array,
    function(data,status){
      if(status == 'success') {
        showMessage('Log in', data.message);
      }
      if (data.message == 'Thank You') {
        window.location.href = '/mainpage';
      }
      else if (data.message == 'Wrong Password') {
        data = data.message + '<br>' + '<a href="/forgotPassword" class="forgot-pwd">Forgot password?</a>';
        $(".error-box p").html(data);
      }
      else {
        data = data.message + '<br>' + '<a href="/register" class="forgot-pwd">New User?</a>';
        $(".error-box p").html(data);
      }
    });
  }

  function signUpValidation(e) {
    $(".error-box p").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    let array = convertToArray(e);
    $.post("/postSignUp",array,
      function(data,status){
        if (status == 'success') {
          showMessage('Sign Up', data.message);
          if (data.message == 'Thank You') {
            window.location.href = '/mainpage';
          }
        }
    });
  }

  function forgotPwd(e) {
    $(".error-box p").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    let array = convertToArray(e);
    $.post('/forgotPwdVerificaton',array,
    function(data,status){
      if (status == 'success') {
        showMessage('Verify', data.message);
      }
    });
  }
  function resetPwd(e) {
    $(".error-box p").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    let array = convertToArray(e);
    $.post('/resetPwdValidation',array,
    function(data,status){
      if (status == 'success') {
        showMessage('Reset Password', data.message);
      }
      if (data.message == 'success') {
        data = 'Reset was Successful' + '<br>' + '<a href="/login" class="forgot-pwd">Login?</a>';
        $(".error-box p").html(data);
      }
    });
  }
});
