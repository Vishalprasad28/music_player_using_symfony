$(document).ready(function(){
  $('.add-song').click(function(){
    $('.song-upload-form').toggleClass('form-hider');
    $('.cross').toggleClass('cancel-btn');
  });

  $('.song-upload-form').submit(function(e){
    e.preventDefault();
    var data = new FormData(this);
    songUpload(data);
  });

  //All Functions here
  function convertToArray(e) {
    var array = {};
    e.forEach(element => {
      array[element['name']] = element['value'];
    });
    return array;
  }

  function songUpload(e) {
    $(".error-box p").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    $.ajax({
      url: '/upload',
      type: 'POST',
      data: e,
      success: function (data, status) {
        $('#submit').html('Submit');
        $('#submit').attr('disabled', false);
       console.log(data.message);
      },
      cache: false,
      contentType: false,
      processData: false
  });
  }
})
