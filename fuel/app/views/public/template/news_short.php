<?php 
if(Uri::segment(1) == 'rpg')
  $style = 'style="width:640px"';
else if(empty($picture_1))
  $style = 'style="width:640px"';
else
  $style = '';
?>
<article class="news clearfix">
    <div class="left pic">
      <?php if(isset($picture_1)): ?>
        <img src="<?php print $picture_1 ?>">
      <?php endif; ?>
    </div>
  <section class="clearfix left" <?php print $style ?>>
    <hgroup>
      <h1 class="left"><?php print $title; ?></h1>
      <h5 class="right"><?php print $time; ?></h5>
    </hgroup>
    <p>
      <?php print $short_text; ?>
      <?php if(!empty($fullview_link)): ?>
      ...<a href="<?php print $fullview_link ?>"><?php print __('news.more'); ?></a>
      <?php endif; ?>
    </p>
  </section>
</article>
