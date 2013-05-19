$(function() {

  


var hover_restore_state_1 = $(".active_language.active_1,.navigation div.navigation-link").clone();
$(document).on("mouseenter",".active_language.active_1,.navigation div.navigation-link",function() {
    $(this).animate({

      backgroundColor : '#ccc7ff'

    });

});
$(document).on("mouseleave",".active_language.active_1,.navigation div.navigation-link",function() {
$(".active_language.active_1,.navigation div.navigation-link").replaceWith(hover_restore_state_1.clone());
});});