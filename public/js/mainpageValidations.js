$(document).ready(function(){
  var offset = 0;
  getAllSongs(offset);

  $('.menu-container').click(function(){
    $('.menu').toggleClass('form-hider');
  });

  $('.add-song').click(function(){
    $('.song-upload-form').toggleClass('form-hider');
    $('.cross').toggleClass('cancel-btn');
  });

  $('.song-upload-form').submit(function(e){
    e.preventDefault();
    
    songUpload(this);
  });

  //All Functions here
  function showMessage(btn, message) {
    $('#submit').attr('disabled',false);
    $('#submit').html(btn);
    $(".error-box").html(message);
  }

  function convertToArray(e) {
    var array = {};
    e.forEach(element => {
      array[element['name']] = element['value'];
    });
    return array;
  }

  function getAllSongs(e) {
    $.post('/fetchSongs',{ offset: e },function(data,status){
      if (status == 'success') {
        $('.posts').append(data);
        offset = $('.posts').children().length;
      }
    });
  }

  function getRecentSong() {
    $.post('/fetchRecentSong',function(data,status){
      if (status == 'success') {
        $('.posts').prepend(data);
        offset = $('.posts').children().length;
      }
    });
  }

  function songUpload(form) {
    var e = new FormData(form);
    $(".error-box").html("");
    $('#submit').html('<img src="assets/loader.gif" alt="">');
    $('#submit').attr('disabled',true);
    $.ajax({
      url: '/upload',
      type: 'POST',
      data: e,
      success: function (data, status) {
        if (status == 'success') {
         showMessage('Post', data.message);
         if (data.message == 'Uploaded') {
           form.reset();
           getRecentSong();
         }
        }
      },
      cache: false,
      contentType: false,
      processData: false
  });
  }

  $('.load-more-button').click(function(){
    getAllSongs(offset);
  })
})
