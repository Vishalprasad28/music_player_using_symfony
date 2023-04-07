$(document).ready(function(){
  var offset = 0;
  getAllSongs(offset);

  //Play the song on apage after cklicking on a post
  $('.posts').on('click','.song-container',function(){
    var song_id = $(this).attr('song-id');
    $.post("/fetchSongById",
    {
      songId: song_id
    },
    function(data,status) {
      if (status == 'success')  {
        if (data.message == 'success') {
          window.location.href = '/player';
        }
      }
    });
  });
  
  //menu bar functionality
  $('.menu-container').click(function(){
    $('.menu').toggleClass('form-hider');
  });

  //Opening the song upload form on clicking on it
  $('.add-song').click(function(){
    $('.song-upload-form').toggleClass('form-hider');
    $('.cross').toggleClass('cancel-btn');
  });

  //song upload formn functionalilty
  $('.song-upload-form').submit(function(e){
    e.preventDefault();
    songUpload(this);
  });

    //load ,more button functionality
    $('.load-more-button').click(function(){
      getAllSongs(offset);
    })

  //All Functions here

  //Function to intriduce the Load More Functionality
  function introduceLoadMoreBtn() {
    if (offset < 8) {
      $('.load-more-button').css('display','none');
    }
    else {
      $('.load-more-button').css('display','block');
    }
  }

  //Function to show the message box
  function showMessage(btn, message) {
    $('#submit').attr('disabled',false);
    $('#submit').html(btn);
    $(".error-box").html(message);
  }

  //fetching the songs
  function getAllSongs(e) {
    $.post('/fetchSongs',{ offset: e },function(data,status){
      if (status == 'success') {
        if (data == '' && offset == 0) {
          $('.posts').html('');
        }
        else if (offset == 0) {
          $('.posts').html(data);
        }
        else {
          $('.posts').append(data);
        }
        offset = $('.posts').children().length;
        introduceLoadMoreBtn();
      }

    });
  }

  //getting the recently uploaded song
  function getRecentSong() {
    $.post('/fetchRecentSong',function(data,status){
      if (status == 'success') {
        if (offset == 0) {
          $('.posts').html(data);
          introduceLoadMoreBtn();
        }
        else {
          $('.posts').prepend(data);
          introduceLoadMoreBtn();
        }
        offset = $('.posts').children().length;
      }
    });
  }

  //song upload functionallity
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
})
