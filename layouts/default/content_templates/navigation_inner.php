<li>
<?php if($image_is_shown && $image_exists): ?>
	<div class="navigation-image" onClick="javascript:window.location.href='<?php print $link; ?>'">
		<img alt="<?php print $label ?>" src="<?php print $image ?>" />
	</div>
	<a target="<?php print $target; ?>" class="<?php print $active_class; ?>" href="<?php print $link; ?>"><?php print $label; ?></a>
<?php else: ?>
	<a target="<?php print $target; ?>" class="<?php print $active_class; ?>" href="<?php print $link; ?>"><?php print $label; ?></a>
<?php endif;?>
{{INNER}}
</li>