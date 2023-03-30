$(document).ready(function(){
  var limit = 5;
  var offset = 0;
  $.post('controller/allsongfetchcontroller.php', {
    limit: limit,
    offset: offset
  },function(data,status){
    if (status == 'success') {
      $('.posts').append(data);
      offset = $('.posts').children().length;
    }
  });
  $('.load-more-button').click(function(){
    $.post('controller/allsongfetchcontroller.php', {
      limit: limit,
      offset: offset
    },function(data,status){
      if (status == 'success') {
        $('.posts').append(data);
        offset = $('.posts').children().length;
      }
    });
  });
});
