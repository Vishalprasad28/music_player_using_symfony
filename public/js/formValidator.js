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

  // All Function Here
  function convertToArray(e) {
    var array = {};
    e.forEach(element => {
      array[element['name']] = element['value'];
    });
    return array;
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
        $('#submit').attr('disabled',false);
        $('#submit').html('Log in');
      }
      $(".error-box p").html(data.pwd);
    });
  }

  function signUpValidation(e) {
    $(".error-box p").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    let array = convertToArray(e);
    $.post("/postSignUp",array,
      function(data,status){
        if (status == 'success') {
          $('#submit').attr('disabled',false);
          $('#submit').html('Sign Up');
          $(".error-box p").html(data.message);
          $(".error-box p").html(data.message);
          if (data.message != 'Thank You') {
          }
          else {
            window.location.href = '/mainpage';
          }
        }
    });
  }
});
