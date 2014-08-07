<h3>
	<?php print __('steps.1.login_header') ?>
</h3>
<?php
	print Form::open(array('action'=>'admin/install/2','class'=>'form_style_1'));
?>



		<?php

			print __('steps.1.online_description');

            print Form::input('host','localhost',array('tabindex'=>1));

			print Form::input('username',__('steps.1.user'),array('tabindex'=>2));

			print Form::input('password',__('steps.1.pass'),array('tabindex'=>3));
		?>
		<h3>
			<?php print __('steps.1.db_header') ?>
		</h3>

		<?php
			print __('steps.1.db_description');

			print Form::input('database','',array('tabindex'=>3));
		?>



<?php

	print Form::submit('submit',__('steps.next'),array('tabindex'=>4));

	print Form::close();

?>

<?php
	if(!empty($errors))
		print '<div class="error">' . $errors . '</div>';
?>