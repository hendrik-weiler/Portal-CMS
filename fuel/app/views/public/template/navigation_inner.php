<li>
	<div <?php if(!$use_default_styles) print 'style="background-color:' . $background_color . '"'; ?> class="navigation-link">
<?php if($image_is_shown && $image_exists): ?>
	<div class="navigation-image" onClick="javascript:window.location.href='<?php print $link; ?>'">
		<img alt="<?php print $label ?>" src="<?php print $image ?>" />
	</div>
<?php endif; ?>
		<a <?php if(!$use_default_styles) print 'style="color:' . $text_color . '"'; ?> target="<?php print $target; ?>" class="<?php print $active_class; ?>" href="<?php print $link; ?>"><?php print $label; ?></a>
		<?php print !empty($description) ? '<p>' . $description . '</p>' : '' ?>
	</div>
{{INNER}}
</li>