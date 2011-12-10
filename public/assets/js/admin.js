$(function() {
	
$('.btn[name=submit]').click(function(e) {
	//e.preventDefault();
	$(this).val('...').removeClass('primary').addClass('success').animate({
		width: '100%'
	},300).delay(400);
});

if(/admin\/language/.test(window.location.href))
{
	$('.delete').prompt({
		header : _prompt.header,
		text : _prompt.text,
		ok : _prompt.ok,
		cancel : _prompt.cancel,
		href : 'attr.href',
		data : []
	});

	$('#language_list').sortable({
		update: function(event, ui) {
			var data = [];
			$.each($('#language_list div'),function(key,value) {
				data[key] = $(this).attr('id');
			});

			$.post(_url + 'admin/language/order/update',{'order' : data});
		}
	});
}

if(/admin\/navigation/.test(window.location.href))
{
	$('.delete').prompt({
		header : _prompt.header,
		text : _prompt.text,
		ok : _prompt.ok,
		cancel : _prompt.cancel,
		href : 'attr.href',
		data : []
	});

	$('#navigation_list').sortable({
		update: function(event, ui) {
			var data = [];
			$.each($('#navigation_list section'),function(key,value) {
				data[key] = $(this).attr('id');
			});

			$.post(_url + 'admin/navigation/order/update',{'order' : data});
		}
	});
}

$('input[name="change"]').click(function() {
	var val = $('select[name="lang_prefix"]').val();
	$.post(_url + 'admin/update/version/' + val,function(data) {
		window.location.href = _url + 'admin/' + _currentPos;
	});
	return false;
});



if(/admin\/sites(\/edit\/[0-9])?/.test(window.location.href))
{
	$('.delete').prompt({
		header : _prompt.header,
		text : _prompt.text,
		ok : _prompt.ok,
		cancel : _prompt.cancel,
		href : 'attr.href',
		data : []
	});

	$('#content_list').sortable({
		update: function(event, ui) {
			var data = [];
			$.each($('#content_list > div'),function(key,value) {
				data[key] = $(this).attr('id');
			});

			$.post(_url + 'admin/content/order/update',{'order' : data});
		}
	});
}

if(/admin\/content\/[0-9]\/edit\/[0-9]\/type\/[0-9]/.test(window.location.href))
{
	elRTE.prototype.options.panels.web2pyPanel = [
	     'bold', 'italic', 'underline', 'forecolor', 'justifyleft', 'justifyright',
	     'justifycenter', 'justifyfull', 'formatblock', 'insertorderedlist', 'insertunorderedlist',
	     'link', 'image', 'flash'
	];
 	elRTE.prototype.options.toolbars.web2pyToolbar = ['web2pyPanel', 'tables'];
	var opts = {
		cssClass : 'el-rte',
		lang     : 'en',
		height   : 450,
		toolbar  : 'web2pyToolbar',
		fmOpen : function(callback) {
	       $('<div id="myelfinder" />').elfinder({
	          url : _url + 'elfinder/connector',
	          lang : 'en',
	          dialog : { width : 900, modal : true, title : 'Files' }, // open in dialog window
	          closeOnEditorCallback : true, // close after file select
	          editorCallback : callback     // pass callback to file manager
	       })
		}
	}
	$('#editor').elrte(opts);
	$('#editor2').elrte(opts);
	$('#editor3').elrte(opts);

	$('.pic_delete').prompt({
		header : _prompt.header,
		text : _prompt.text,
		ok : _prompt.ok,
		cancel : _prompt.cancel,
		href : 'attr.href',
		sendAttr : 'title',
		data : {}
	});
}

if(/admin\/news$/.test(window.location.href))
{
	$('.delete').prompt({
		header : _prompt.header,
		text : _prompt.text,
		ok : _prompt.ok,
		cancel : _prompt.cancel,
		href : 'attr.href',
		data : []
	});
}

if(/admin\/news\/edit\/[0-9]$/.test(window.location.href))
{
	$('.pic_delete').prompt({
		header : _prompt.header,
		text : _prompt.text,
		ok : _prompt.ok,
		cancel : _prompt.cancel,
		href : 'attr.href',
		data : []
	});

	elRTE.prototype.options.panels.web2pyPanel = [
	     'bold', 'italic', 'underline', 'forecolor', 'justifyleft', 'justifyright',
	     'justifycenter', 'justifyfull', 'formatblock', 'insertorderedlist', 'insertunorderedlist',
	     'link', 'image', 'flash', 'code'
	];
 	elRTE.prototype.options.toolbars.web2pyToolbar = ['web2pyPanel', 'tables'];
	var opts = {
		cssClass : 'el-rte',
		lang     : 'en',
		height   : 450,
		toolbar  : 'web2pyToolbar',
		fmOpen : function(callback) {
	       $('<div id="myelfinder" />').elfinder({
	          url : _url + 'elfinder/connector',
	          lang : 'en',
	          dialog : { width : 900, modal : true, title : 'Files' }, // open in dialog window
	          closeOnEditorCallback : true, // close after file select
	          editorCallback : callback     // pass callback to file manager
	       })
		}
	}
	$('#editor').elrte(opts);
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
}

if(/admin\/accounts\/edit/.test(window.location.href)
	|| /admin\/accounts\/add/.test(window.location.href))
{
	$('#tabs_menu li:first,#tabs_navi li:first,.pill-content div:first').addClass('active');

}


});