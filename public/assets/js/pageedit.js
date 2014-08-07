$(function() {
    var maxHeight = [];
    $.each($('.globalmenu'), function(key, obj) {
        maxHeight.push($(obj).height());
    });
    maxHeight = maxHeight.sort(function(a,b) {
        return a - b;
    });
    var height = maxHeight[maxHeight.length - 1];
    $('.backbutton').height(height);
    $('.backbutton img').css('marginTop', height/2 - 7);

    $('.backbutton').click(function() {
        window.location.href = $(this).find('a').attr('href');
    });

    $('.icon.move').click(function(e) {
        e.preventDefault();
        return false;
    });
});