<div id="<?php print $group; ?>"></div>
  <h1><?php print $title ?></h1>
  <img id="<?php print $group; ?>_picture" src="<?php print $picture ?>" >
  <div id="flash_<?php print $group; ?>"></div>
  <script>
  $(function() {
   
    swfobject.embedSWF("<?php print $swfPath ?>", "flash_<?php print $group; ?>", "<?php print $width ?>", "<?php print $height ?>", "9.0.0","<?php print Uri::create("assets/swf/expressInstall.swf") ?>",<?php print $params; ?>, {wmode : "<?php print $wmode; ?>"});
    $('#<?php print $group; ?>_picture').hide();
   
  });
  </script>