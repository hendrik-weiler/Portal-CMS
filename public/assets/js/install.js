$(function() {

	$('#form_username,#form_password,#form_online_username,#form_online_password').click(function() {
		$(this).select();
	});

	$('#tabs div').hide().eq(0).show();
	$('#tabs a').removeClass('active_tab').eq(0).addClass('active_tab');
	
	$('#tabs a').click(function() {
		var index = $('#tabs a').index(this);

		$('#tabs a').removeClass('active_tab').eq(index).addClass('active_tab');
		$('#tabs div').hide().eq(index).show();
	});

    if (window.PIE) {
        $('#tabs a,#tabs a:hover,.active_tab,#tabs div').each(function() {
            PIE.attach(this);
        });
    }

});