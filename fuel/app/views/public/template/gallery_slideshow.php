<h1>
	<?php print $label; ?>
</h1>
<p>
    <div id="<?php print $group ?>" class="theme-default nivoSlider" style="width:<?php print $slideshow_width ?>px;height:<?php print $slideshow_height ?>px;">

      <?php
        $counter = 0;
        foreach($pictures as $pic)
        {
          if(empty($pic['description']))
            $caption = '';
          else
            $caption = 'title="#caption-' . $counter . '"';

          print '<img ' . $caption . ' src="' . $pic['original'] . '" />';
          $counter++;
        }
      ?>

  </div>
    <?php
        $counter = 0;
        foreach($pictures as $pic)
        {
            if(!empty($pic['description']))
                print '<div id="caption-' . $counter . '" class="nivo-html-caption">' . $pic['description'] . '</div>';
            $counter++;
        }
    ?>
</p>
<script>
$(function() {
    $("#<?php print $group ?>").nivoSlider({
            effect:"random",
            slices:15,
            boxCols:8,
            boxRows:4,
            animSpeed:800,
            pauseTime:5000,
            startSlide:0,
            directionNav:true,
            directionNavHide:true,
            controlNav:false,
            controlNavThumbs:false,
            controlNavThumbsFromRel:true,
            keyboardNav:true,
            pauseOnHover:true,
            manualAdvance:false
    });
});
</script>