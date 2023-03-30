$(document).ready(function(){
  $('.add-song').click(function(){
    $('.song-upload-form').toggleClass('form-hider');
    $('.cross').toggleClass('cancel-btn');

  });
  $('.song-upload-form').submit(function(e){
     e.preventDefault();
     $(".error-box p").html("");
    $('#submit').html('<img src="public/assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    var formData = new FormData(this);
    var submit = $('.submit_button').val();
    $.ajax({
      url: 'controller/songuploadcontroller.php',
      type: 'POST',
      data: formData,
      success: function (data) {
        $('#submit').html('Submit');
        $('#submit').attr('disabled', false);
        if (data != 'uploaded') {
          $('.error-box').html(data);
        }
        else {
          $('#song').val("");
          $('#name').val("");
          $('#singer').val("");
          $('.genere').val("");
          $('.error-box').html(data);
          $.post('controller/specificsongcontroller.php',{
            submit: submit
          },
          function(data,status,error){
            if (status == 'success') {
              $('.posts').prepend(data);
            }
          });
        }
      },
      cache: false,
      contentType: false,
      processData: false
  });
  });
});