<div class="flvvideoplayer player_<?php print $content_id ?>">

	<h2><?php print $title ?></h2>

	<div id="player_<?php print $content_id ?>_video"></div>
	<script type="text/javascript">
	var flashvars = {};
	flashvars.image = "<?php print $previewpath ?>";	
	
	flashvars.movie = "<?php print $filepath ?>";
	flashvars.autoplay = "<?php print $autoplay ?>";			
	flashvars.loop = "false";
	
	flashvars.autohide = "<?php print $autohide ?>";
	
	flashvars.fullscreen = "<?php print $fullscreen ?>";
	
	flashvars.color_text = "0x<?php print str_replace('#','',$color_text) ?>";
	flashvars.color_seekbar = "0x<?php print str_replace('#','',$color_seekbar) ?>";
	flashvars.color_loadingbar = "0x<?php print str_replace('#','',$color_loadingbar) ?>";
	flashvars.color_seekbarbg = "0x<?php print str_replace('#','',$color_seekbarbg) ?>";
	
	flashvars.color_button_out = "0x<?php print str_replace('#','',$color_button_out) ?>";
	flashvars.color_button_over =  "0x<?php print str_replace('#','',$color_button_over) ?>";
	flashvars.color_button_highlight =  "0x<?php print str_replace('#','',$color_button_highlight) ?>";
	
	var params = {};
	params.allowfullscreen = "true";
	params.allowscriptaccess = "always";
	params.bgcolor = "#000000";
	
	var attributes = {};
	attributes.align = "middle";

	swfobject.embedSWF("<?php print $videoplayerpath ?>", "player_<?php print $content_id ?>_video", "<?php print $width ?>", "<?php print $height ?>", "9.0.0","<?php print Uri::create("assets/swf/expressInstall.swf") ?>", flashvars, params, attributes);
	

	</script>
</div>