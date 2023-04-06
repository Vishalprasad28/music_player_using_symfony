$(document).ready(function(){
  var offset = 0;
  alert('gjkhn');
  getFavSongs(offset);

  //Functions here

  //fetchinf the all favourite songs
  //fetching the songs
  function getFavSongs(e) {
    $.post('/favouritesFetcher',{ offset: e },function(data,status){
      if (status == 'success') {
        console.log(data);
        // $('.favPosts').append(data);
        // offset = $('.posts').children().length;
      }
    });
  }
});