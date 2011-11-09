<h3>
	<?php print __('steps.3.finish_header') ?>
</h3>

<?php

	print Form::open(array('action'=>'admin/install/3','class'=>'form_style_1'));

	print __('steps.3.finish_description');

	print Form::submit('redirect',__('steps.3.button'),array('tabindex'=>4));

	print Form::close();

?>