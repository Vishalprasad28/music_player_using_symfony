$(document).ready(function(){
  $('form').submit(function(e){
    $(".error-box p").html("");
    e.preventDefault();
    $('#submit').html('<img src="public//assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    var formData = new FormData(this);
    $.ajax({
      url: 'controller/profileupdate.php',
      type: 'POST',
      data: formData,
      success: function (data) {
        $('#submit').html('Submit');
        $('#submit').attr('disabled', false);
        $('.error-box ').html(data);
        },
      cache: false,
      contentType: false,
      processData: false
    });
  });
});
