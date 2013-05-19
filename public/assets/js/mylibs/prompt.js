$(function() {
		var overlay = '<div id="overlay"></div>';
		$('body').append(overlay);

		$.box = $('<section id="prompt"></section>');
		$($.box).append('<h3></h3>');
		$($.box).append('<p></p>');

		var div_buttons = $('<div></div>');
		$(div_buttons).append('<button></button>');
		$(div_buttons).append('<button></button>');

		$($.box).append(div_buttons);
		$($.box).appendTo('body');

		$('#overlay,#prompt').hide();
});

(function( $ ) {
	$.fn.prompt = function(options) {

    this.each(function() {

		$('#prompt').find('h3').html(options.header);
		$('#prompt').find('p').html(options.text);
		$('#prompt').find('button:eq(0)').html(options.ok);
		$('#prompt').find('button:eq(1)').html(options.cancel);

		$(this).click(function() {

			var result = /(.*)\.(.*)/.exec(options.href);

			if(result[1] == 'attr')
			{
				options.href = $(this).attr(result[2]);
			}
			
			if(options.sendAttr)
				options.data.attr = $(this).attr(options.sendAttr);
				
			$('#overlay').css({
				width : '100%',
				height : $(document).height()
			});
			$('#prompt').css({
				top : ($(window).height() / 2) - $('#prompt').height(),
				position : 'fixed'
			});
			$('#overlay,#prompt').fadeIn();
			return false;
		});

		$('#prompt button:eq(0)').click(function() {

			$.post(options.href,options.data,function(response) {
				window.location.reload();
			});
		});

		$('#prompt button:eq(1),#overlay').click(function() {
			$('#overlay,#prompt').fadeOut();
		});
    });

};


})( jQuery );