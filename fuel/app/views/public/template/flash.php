<div id="<?php print $group; ?>"></div>
  <h1><?php print $title ?></h1>
  <img id="<?php print $group; ?>_picture" src="<?php print $picture ?>" >
  <div id="flash_<?php print $group; ?>"></div>
  <script>
  $(function() {
   if($.flash.available)
   {
     $('#flash_<?php print $group; ?>').flash(
      {
        swf: '<?php print $swfPath; ?>',
        flashvars: <?php print $params; ?>,
        wmode : '<?php print $wmode; ?>',
        height : <?php print $height; ?>,
        width : <?php print $width; ?>
      }
    );
    $('#<?php print $group; ?>_picture').hide();
   }
  });
  </script>