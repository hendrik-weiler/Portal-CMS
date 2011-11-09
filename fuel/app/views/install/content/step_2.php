<h3>
	<?php print __('steps.2.login_header') ?>
</h3>

<?php

	print __('steps.2.acc_description');

	print Form::open(array('action'=>'admin/install/3','class'=>'form_style_1'));

	print Form::input('username',__('steps.1.user'),array('tabindex'=>1));

	print Form::input('password',__('steps.1.pass'),array('tabindex'=>2));

	print Form::submit('submit_2',__('steps.next'),array('tabindex'=>3));

	print Form::close();
?>

<?php
	if(!empty($errors))
		print '<div class="error">' . $errors . '</div>';
?>