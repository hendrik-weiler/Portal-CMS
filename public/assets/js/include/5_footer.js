$(function() {
  var win_height = $(window).height();
  var doc_height = $('body').height();

  if(doc_height < win_height)
    $('footer').css('marginTop',( ((win_height - doc_height) - $('footer').height()) / 2 ) - 70);
});