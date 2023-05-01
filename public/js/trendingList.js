$(document).ready(function(){
    var offset = 0;
    getTrendingList(offset);

        //Function to intriduce the Load More Functionality
    function introduceLoadMoreBtn() {
        if (offset < 8) {
        $('.load-more-button').css('display','none');
        }
        else {
        $('.load-more-button').css('display','block');
        }
    }

    //load ,more button functionality
    $('.load-more-button').click(function(){
        getTrendingList(offset);
    });
  
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

  //Fetch the trending songs
  function getTrendingList(e) {
    $.post('/getTrending',{ offset: e },function(data,status){
        if (status == 'success') {
            if (data == '' && offset == 0) {
              $('.trendingList').html('<center><h2>EMPTY LIST :(</h2></center>');
              $('.trendingList').css('height','auto');
              $('.load-more-button').css('display','none');
            }
            $('.trendingList').append(data);
            offset = $('.posts').children().length;
    };
  });
}

    //menu bar functionality
    $('.menu-container').click(function(){
        $('.menu').toggleClass('form-hider');
    });

    //Play the song on apage after cklicking on a post
    $('.trendingList').on('click','.song-container',function(){
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
});