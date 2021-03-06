$(function() {
	
$('.btn[name=submit]').click(function(e) {
	//e.preventDefault();
	$(this).val('...').removeClass('primary').addClass('success').animate({
		width: '100%'
	},300).delay(400);
});

$('#form_change').click(function(e) {
	//e.preventDefault();
	$(this).val('...').removeClass('primary').addClass('success').animate({
		width: '200px'
	},300).delay(400);
});

function updateLanguageSort() {
	var data = [];
	$.each($('#language_list .language_entry'),function(key,value) {
		data[key] = $(this).attr('id');
	});

	$.post(_url + 'admin/language/order/update',{'order' : data});
}

if(/admin\/language/.test(window.location.href))
{

	var ltext = $('#language_list div.startlanguage-text');

	$('#language_list').sortable();
	$('#language_list').bind('sortupdate',function(event, ui) {
			updateLanguageSort();

			$('#language_list .language_entry').removeClass('startlanguage');
			$('#language_list .language_entry').eq(0).addClass('startlanguage');
			$('#language_list .language_entry.startlanguage-text').remove();
			$('#language_list .language_entry').eq(0).append(ltext);
	});

	$('#language_list div').eq(0).addClass('startlanguage');
	updateLanguageSort();
}

if(/admin\/navigation/.test(window.location.href))
{

	$('body').on('click','#addnav',function(e) {
		e.preventDefault();
		var nav_group = $('#addnav_tpl li').clone();
		nav_group.css('display','block');

		nav_group.find('.nav_menu a:eq(0)').off();
		nav_group.find('.nav_menu a:eq(1)').off();

		nav_group.find('.nav_menu a:eq(0)').click(function(e) {
			e.preventDefault();
			var nav_group = $('#editnav_tpl').html();
			var li = $('#groups li').eq($.curnav);
			li.html(nav_group.replace('|title|',$(li).find('a').html()));
		});

		nav_group.find('.nav_menu a:eq(1)').click(function(e) {
			e.preventDefault();
			var li = $('#groups li').eq($.curnav);
			var _id = li.attr('id');

			$.get(_url + 'admin/navigation/group/delete',{id : _id},function() {
				window.location.reload();
			});
		});


		$(this).parent().html(nav_group);
	});

	/* ----------------------------------------------- */

	$.curnav = 0;

	$('body').on('mousedown','#groups li',function(e) {
		if(e.which == 3 && $(this).find('a').attr('id') != 'addnav' && $(this).find('input').length == 0)
		{
			e.preventDefault();
			var pos = $(this).position();
			$('.nav_menu').css({
				top : pos.top + 15,
				left : pos.left + 15,
				display : 'block',
			});
			$.curnav = $('#groups li').index(this);
		}
	});



	$('body').on('click','#rename_group',function(e) {
		e.preventDefault();

		var li = $('#groups li').eq($.curnav);
		var _id = li.attr('id');
		var _name = li.find('input[type=text]').val();
                
        if(_name != '')
        {
            $.get(_url + 'admin/navigation/group/edit',{id : _id,name: _name});

            var nav_group = $('#new_nav_tpl').html()
                            .replace('|url|',_url + 'admin/navigation/' + _id)
                            .replace('|title|',_name)
                            .replace('<li>','')
                            .replace('</li>','');
            li.html(nav_group);
        }
	});

	$(document).click(function() {
		$('.nav_menu').hide();
	});

	$(document).bind("contextmenu",function(e){
	  //return false;
	});

	/* ----------------------------------------------- */

	$('#submit_group').on('click',function(e) {
		e.preventDefault();
		var title = $('input[name=group_title]').val();
        if(title != '')
        {
            var new_nav;
            var self = $(this);
            $.get(_url + 'admin/navigation/group/new',{group : title},function(data) {
                new_nav = $('#new_nav_tpl').html()
                                                                .replace('|url|',_url + 'admin/navigation/' + data)
                                                                .replace('|title|',title);

                $('#groups').append(new_nav).append('<li><a id="addnav" href="#">+</a></li>');
                self.parent().remove();
                window.location.reload();
            });
        }
	});

	$('#submit_cancel').on('click',function(e) {
		e.preventDefault();
		$(this).parent().parent().html('<a id="addnav" href="#">+</a>');
	});
}

$('input[name="change"]').click(function() {
	var val = $('select[name="lang_prefix"]').val();
	$.post(_url + 'admin/update/version/' + val,function(data) {
		window.location.href = _url + 'admin/' + _currentPos;
	});
	return false;
});



if(/admin\/sites(\/edit\/[0-9]+)?/.test(window.location.href))
{

	$('#content_list').sortable({
		update: function(event, ui) {
			var data = [];
			$.each($('#content_list > div'),function(key,value) {
				data[key] = $(this).attr('id');
			});

			$.post(_url + 'admin/content/order/update',{'order' : data, 'site_id' : _site_id});
		}
	});
}


if(/admin\/advanced/.test(window.location.href))
{
	$('.delete').prompt({
		header : _prompt.header,
		text : _prompt.text,
		ok : _prompt.ok,
		cancel : _prompt.cancel,
		href : 'attr.href',
		data : []
	});


	$('.choose_layout').click(function() {
		$(this).parent().append(_wait);
		$.get(_url + 'admin/advanced/layout/choose',{name : $(this).val()},function() {
			window.location.reload();
		});
	});
}

if(/admin\/accounts\/edit/.test(window.location.href)
	|| /admin\/accounts\/add/.test(window.location.href))
{
	$('#tabs_menu li:first,#tabs_navi li:first,.pill-content div:first').addClass('active');

}


});