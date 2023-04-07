$(document).ready(function() {

  //Handling the login process
  $('#loginForm').submit(function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    loginValidation(formData);
  });
  
  //Handling the signup process
  $('#signUpForm').submit(function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    signUpValidation(formData);
  });

  //Handling the forgot-password page
  $('#forgotPwd-form').submit(function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    forgotPwd(formData);
  });

  //handling the Reset Password Page
  $('#Reset-Form').submit(function(e){
    e.preventDefault();
    let formData = $(this).serializeArray();
    resetPwd(formData);
  });

  //Update pofile Form Handling
  $('#update-profile-form').submit(function(e){
    e.preventDefault();
    update(this);
  });
  
  // All Function Here

  //Function to convert the ford data into an array
  function convertToArray(e) {
    var array = {};
    e.forEach(element => {
      array[element['name']] = element['value'];
    });
    return array;
  }

  //Functon to show the message after submission of form
  function showMessage(btn, message) {
    $('#submit').attr('disabled',false);
    $('#submit').html(btn);
    $(".error-box p").html(message);
  }

  //Function for login Validation
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
  //Functiom for the signup Validation
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

  //Function for the Forgot Password form Validation
  function forgotPwd(e) {
    $(".error-box p").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    let array = convertToArray(e);
    $.post('/forgotPwdVerificaton', array, 
    function(data,status){
      if (status == 'success') {
        showMessage('Verify', data.message);
      }
    });
  }

  //Function for the Reset Password form Validation
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

  //Function for the Update Profile form Validation
  function update(form) {
    $(".error-box p").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    var array = new FormData(form);
    $.ajax({
      url: '/updateProfileValidation',
      type: 'POST',
      data: array,
      success: function (data, status) {
       
        if (status == 'success') {
         showMessage('Update', data.message);
        }
      },
      cache: false,
      contentType: false,
      processData: false
  });
  }
});
