<?php
	print Form::open(array('action'=>'admin/settings/edit','id'=>'settings_form','class'=>'form_style_1'));
?>
<section id="settings" class="clearfix">
	<div class="left language">
	<h3>
		<?php print __('settings.header.language') ?>
	</h3>

<div class="clearfix">
 <?php print Form::label(__('settings.lang')); ?>
 <div class="input">
			<?php
				$langs = model_lang::to_selectbox();

				print Form::select('language',model_auth::$user['language'],$langs);
			?>
  </div>
</div>


	</div>
</section>
<div class="actions">
<?php
	print Form::submit('submit',__('constants.save'),array('class'=>'btn primary'));

	print Form::close();
?>
</div>