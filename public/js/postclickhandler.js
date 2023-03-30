$(document).ready(function(){
  $('.posts,.favPosts').on('click','.song-container',function(){
    var song_id = $(this).attr('song-id');
    $.post("controller/songplayercontroller.php",
    {
      songId: song_id
    },
    function(data,status) {
      if (status == 'success')  {
        window.location.href = '/player';
      }
    });
  });
});

