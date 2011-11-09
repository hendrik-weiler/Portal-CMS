<h1>
	<?php print $label; ?>
</h1>
<p>
	<?php
		foreach($pictures as $pic)
		{
			print '<a title="' . $pic['description'] . '" href="' . $pic['original'] . '" class="' . $group . '" href=""><img src="' . $pic['thumb'] . '" /></a>';
		}
	?>
</p>
<script>$(".<?php print $group ?>").colorbox({rel:'<?php print $group ?>'});</script>