$(document).ready(function(){
  var offset = 0;
  getFavSongs(offset);

    //menu bar functionality
    $('.menu-container').click(function(){
      $('.menu').toggleClass('form-hider');
    });

  //Play the song on apage after cklicking on a post
  $('.favPosts').on('click','.song-container',function(){
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

  //Functions here

  //fetchinf the All favourite songs
  function getFavSongs(e) {
    $.post('/favouritesFetcher',{ offset: e },function(data,status){
      if (status == 'success') {
        if (data == '' && offset == 0) {
          $('.favPosts').html('<center><h2>EMPTY LIST :(</h2></center>');
          $('.favPosts').css('height','auto');
          $('.load-more-button').css('display','none');
        }
        $('.favPosts').append(data);
        offset = $('.posts').children().length;
    }
  });
  }
});