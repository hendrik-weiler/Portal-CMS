<h3>
	<?php print __('steps.1.login_header') ?>
</h3>
<?php
	print Form::open(array('action'=>'admin/install/2','class'=>'form_style_1'));
?>
<div id="tabs">
	<ul class="clearfix">
		<li><a href="#" title="offline"><?php print __('steps.1.offline_db'); ?></a></li>
		<li><a href="#" title="online"><?php print __('steps.1.online_db'); ?></a></li>
	</ul>

	<div id="offline">
		<?php

			print __('steps.1.offline_description');

			print Form::input('username',__('steps.1.user'),array('tabindex'=>1));

			print Form::input('password',__('steps.1.pass'),array('tabindex'=>2));
		?>
		<h3>
			<?php print __('steps.1.db_header') ?>
		</h3>

		<?php
			print __('steps.1.db_description');

			print Form::input('database','',array('tabindex'=>3));
		?>
	</div>
	<div id="online">
		<?php

			print __('steps.1.online_description');

			print Form::input('online_username',__('steps.1.user'),array('tabindex'=>5));

			print Form::input('online_password',__('steps.1.pass'),array('tabindex'=>6));
		?>
		<h3>
			<?php print __('steps.1.db_header') ?>
		</h3>

		<?php
			print __('steps.1.db_online_description');

			print Form::input('online_database','',array('tabindex'=>3));

		?>
	</div>
</div>

<?php

	print Form::submit('submit',__('steps.next'),array('tabindex'=>4));

	print Form::close();

?>

<?php
	if(!empty($errors))
		print '<div class="error">' . $errors . '</div>';
?>