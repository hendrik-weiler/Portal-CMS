// Modified tooltip component from portal cms 2
if(pcms2 === undefined)
	var pcms2 = new Object();

$(function() {
	var pcms2_Tooltip = function() 
	{
		this.load_tool_tip = function(url)
		{
			$.ajax({
			    type: "GET",
				url: url,
				dataType: "xml",
				success: function(xml) {
			 		$(xml).find('tooltip[language=' + _language + ']').each(function(key,value) {
			 			var selector = $(value).attr('selector');
			 			var position = $(value).attr('position');
			 			var text = $.trim($(value).text());
			 			$(selector)
			 			   .attr('rel','tooltip')
			 			   .attr('data-original-title',text)
			 			   .tooltip({
			 				placement : position
			 			});
			 		});
				}
			});
		}
	}

	pcms2['Tooltip'] = new pcms2_Tooltip();
	
	pcms2.Tooltip.load_tool_tip(_url + 'assets/xml/tooltip/global.xml');
});