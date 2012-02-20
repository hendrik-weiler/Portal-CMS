<div class="news clearfix">
    <div class="left pic">
      <?php if(isset($picture_1)): ?>
        <img alt="<?php print $title; ?>" src="<?php print $picture_1 ?>">
      <?php endif; ?>
    </div>
  <div class="clearfix left content" <?php print isset($style) ? $style : '' ?>>
      <h1><?php print $title; ?></h1>
      <div class="date">
        <?php print asset_manager_get('img->include->clock',array('alt'=>'clock icon')) ?>
        <span><?php print $time; ?></span>
      </div>
    <p>
      <?php print $short_text; ?>
      ...<a href="<?php print $fullview_link ?>"><?php print __('news.more'); ?></a>
    </p>
  </div>
</div>