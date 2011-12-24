<h3>
	<?php print __('languages.' . $mode . '_lang_header') ?>
</h3>

<?php

$ifEdit = (!empty($id)) ? '/' . $id : '';

print Form::open(array('action'=>'admin/language/' . $mode . $ifEdit,'id'=>'languages_form','class'=>'form_style_1'));
?>

<div class="clearfix">
 <?php print Form::label(__('languages.form.lang')); ?>
 <div class="input">
    <?php print Form::input('label',$label); ?>
  </div>
</div>

<div class="clearfix">
 <?php print Form::label(__('languages.form.lang_prefix')); ?>
 <div class="input">
    <?php print Form::input('prefix',$prefix); ?>
  </div>
</div>

<?php
print '<div class="actions">';

print Form::submit('submit',__('languages.form.' . $mode . '_button'),array('class'=>'btn primary'));

print '</div>';

print Form::close();

?>

<hr />
<h5>
	<?php print __('languages.sortable') ?>
</h5>
<section id="language_list">
<?php

	$languages = model_db_language::find('all',array(
		'order_by' => array('sort'=>'ASC')
	));

	foreach($languages as $lang)
	{
		print '<div id="' . $lang['id'] . '">';

		print $lang['label'] . ' - ' . $lang['prefix'] . ' - ';

		print '<a href="' . Uri::create('admin/language/edit/' . $lang['id']) . '">' . __('constants.edit') . '</a>';

		if(count($languages) > 1)
			print ' <a class="delete" href="' . Uri::create('admin/language/delete/' . $lang['id']) . '">' . __('constants.delete') . '</a>';

		print '</div>';
	}

?>
</section>

<img id="moveable_language" src="<?php print Uri::create('assets/img/admin/moveable.png') ?>" alt="Moveable">