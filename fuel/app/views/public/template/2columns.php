<article id="<?php print $group; ?>" class="textcontainer clearfix">
  <?php if(!empty($label)): ?>
	<h1>
		<?php print $label; ?>
	</h1>
  <?php endif; ?>

  <div class="col_1" style="float:left;width:45%">
  	<p>
  		<?php print $text; ?>
  	</p>
  </div>
  <div class="col_2" style="float:left;width:45%">
    <p>
      <?php print $text2; ?>
    </p>
  </div>
</article>