$(function() {
    var triggerOnce = false;
    var maxHeight = [];
    $.each($('.globalmenu'), function(key, obj) {
        maxHeight.push($(obj).height());
    });
    maxHeight = maxHeight.sort(function(a,b) {
        return a - b;
    });
    var height = maxHeight[maxHeight.length - 1];
    $('.backbutton, .backbutton label').height(height);
    $('.backbutton img').css('marginTop', height/2 - 7);

    $('.backbutton').click(function() {
        if(!triggerOnce) {
            triggerOnce = true;
            $('.backbutton input[type=submit]').trigger('click');
        }
    });

    $('.more-options-box').hide();
    $('.less-options').hide();

    $('.more-options').click(function(e) {
        e.preventDefault();

        $('.less-options').show();
        $('.more-options').hide();
        $('.more-options-box').show();
    });


    $('.less-options').click(function(e) {
        e.preventDefault();

        $('.more-options').show();
        $('.less-options').hide();
        $('.more-options-box').hide();
    });
});