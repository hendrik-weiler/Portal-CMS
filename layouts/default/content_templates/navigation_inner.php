<li>
<?php if($image_is_shown): ?>
	<div class="navigation-image" onClick="javascript:window.location.href='<?php print $link; ?>'">
		<img alt="<?php print $label ?>" src="<?php print $image ?>" />
	</div>
<?php endif;?>
<a target="<?php print $target; ?>" class="<?php print $active_class; ?>" href="<?php print $link; ?>"><?php print $label; ?></a>
{{INNER}}
</li>