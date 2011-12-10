<?php
# ---------------------------------------------
# Usable variables in a gallery
# 
# static classes (model)
# string $label (titel of content)
# array  $pictures (array of all pictures within the galerie)
#        every picture contains 3 keys
#        [thumb,original,description]
# string $group (unique name)
# ---------------------------------------------

?>
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
            $caption = $pic['description'];

          print '<a href="http://' . $caption . '"><img ' . $caption . ' src="' . $pic['original'] . '" /></a>';
          $counter++;
        }
      ?>

  </div>
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
})
</script>