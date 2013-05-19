<div class="row">
	<?php if(isset($option_form) && is_array($option_form)): ?>
	<div class="span4 option_form">
		<?php print Form::open(array('action'=>Uri::current())); ?>
		<h3><?php print ucfirst($active_plugin) ?></h3>
		<?php
			foreach($option_form['form'] as $name => $value)
			{
				$option_val = isset($active_options[$name]) ? $active_options[$name] : $option_form['default'][$name];
				print str_replace('_',' ',$name);
				if($value == 'textbox')
				{
					print Form::input($name,$option_val);
				}
				else if($value == 'textarea')
				{
					print Form::textarea($name,$option_val);
				}
				else if($value == 'checkbox')
				{
					$checked = array();
					$option_val == 1 and $checked = array('checked'=>'checked');
					print Form::checkbox($name,1,$checked);
				}
				else if(is_array($value))
				{
					print Form::select($name,$option_val,$value);
				}
			}
		?>
		<div class="form-actions">
			<?php print Form::submit('change_options',__('types.1.submit'),array('class'=>'btn primary')) . ' '; ?>
		</div>
		<?php print Form::close(); ?>
	</div>
	<?php endif; ?>
	<div class="span10">
		<?php print Form::open(array('action'=>Uri::current())); ?>
		<div class="row">
		<?php foreach($folder_plugin as $ns => $plugins): ?>
		<?php $ns = str_replace(DS, '', $ns); ?>
			<div class="span4 plugin-container">
				<h3><?php print ucfirst($ns) ?></h3>
				<ul>
					<?php foreach($plugins as $plugin): ?>
					<?php $plugin = str_replace('.php', '', $plugin) ?>
					<?php
						$checked = array();
						if($active_plugin == $ns . '\\' . $plugin)
							$checked = array('checked'=>'checked');
					?>
					<li><?php print Form::radio('active_plugin',$ns . '\\' . $plugin,$checked) . ' ' . $plugin; ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
		</div>
		<div class="form-actions">
			<?php print Form::submit('submit',__('types.1.submit'),array('class'=>'btn primary')) . ' '; ?>
			<?php print Form::submit('back',__('types.1.back'),array('class'=>'btn secondary')); ?>
		</div>
		<?php print Form::close(); ?>
	</div>
</div>