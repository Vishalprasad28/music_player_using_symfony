$(document).ready(function(){
  var limit = 5;
  var isEmpty = 0;
  var offset = 0;
  $.post('controller/favsongfetchcontroller.php', {
    limit: limit,
    offset: offset
  },function(data,status){
    if (status == 'success') {
      if (data == '') {
        $('.favPosts').html('<center><h2>EMPTY LIST :(</h2></center>');
        $('.favPosts').css('height','auto');
        isEmpty = 1;
        $('.load-more-button').css('display','none');
      }
      else {
        $('.favPosts').html(data);
        offset = $('.favPosts').children().length;
      }
    }
  });
  $('.load-more-button').click(function(){
    $.post('controller/favsongfetchcontroller.php', {
      limit: limit,
      offset: offset
    },function(data,status){
      if (status == 'success') {
          $('.favPosts').append(data);
          offset = $('.favPosts').children().length;
      }
    });
  });
});

