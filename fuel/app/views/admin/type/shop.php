<?php print Form::open(array('action'=>'admin/content/' . Uri::segment(3) . '/edit/' . Uri::segment(5) . '/type/15/edit','enctype'=>'multipart/form-data')) ?>
<div class="row">
	<div class="span16">
	<?php foreach ($article_groups as $group): ?>
		<div class="">
			<?php 
			$checked = array();
			in_array($group->id,$selected_checkbox) and $checked = array('checked'=>'checked');
			?>
			<?php print Form::checkbox('group[]', $group->id, $checked) ?> 
			<?php print $group->label ?>
		</div>
	<?php endforeach; ?>
	</div>
</div>
<div class="row actions">
	<?php print Form::submit('confirm',__('types.15.submit'),array('class'=>'btn primary')); ?>
	<?php print Form::submit('back',__('types.15.back'),array('class'=>'btn secondary')); ?>
</div>
<?php print Form::close(); ?>