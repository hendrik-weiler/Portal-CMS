<article id="<?php print $group; ?>" class="textcontainer clearfix">
  <?php if(!empty($label)): ?>
	<h1>
		<?php print $label; ?>
	</h1>
  <?php endif; ?>

  <div class="col_1" style="float:left;width:30%">
  	<p>
  		<?php print $text; ?>
  	</p>
  </div>
  <div class="col_2" style="float:left;width:30%">
    <p>
      <?php print $text2; ?>
    </p>
  </div>
  <div class="col_3" style="float:left;width:30%">
    <p>
      <?php print $text3; ?>
    </p>
  </div>
</article>