<li>
	<div class="navigation-link <?php print $active_class; ?>">
<?php if($image_is_shown && $image_exists): ?>
	<div class="navigation-image" onClick="javascript:window.location.href='<?php print $link; ?>'">
		<img alt="<?php print $label ?>" src="<?php print $image ?>" />
	</div>
<?php endif; ?>
		<a target="<?php print $target; ?>" href="<?php print $link; ?>"><?php print $label; ?></a>
		<?php print !empty($description) ? '<p>' . $description . '</p>' : '<p>&nbsp;</p>' ?>
	</div>
{{INNER}}
</li>