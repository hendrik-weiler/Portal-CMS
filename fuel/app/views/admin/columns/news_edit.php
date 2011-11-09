<h3>
	<?php print __('news.edit.header') ?>
</h3>

<?php print Form::open(array('action'=>Uri::current(),'class'=>'form_style_1','enctype'=>'multipart/form-data')); ?>
<div class="clearfix">
   <?php print Form::label(__('news.edit.title')); ?>
   <div class="input">
      <?php print Form::input('title',$title,array('style'=>'width:300px;')); ?>
    </div>
  </div>
<h3>
	<?php print __('news.edit.pictures') ?>
</h3>
<div class="clearfix news_pictures">
<?php

	print '<div class="left">';

	if(isset($images['picture_1'])) {
		print '<div class="clearfix">';
		print '<img class="left" src="' . Uri::create(str_replace('original/','',$images['picture_1'])) . '" />';
		print '<a class="pic_delete left" href="' . Uri::create('admin/news/picture/delete/' . $id . '/picture_1') . '">' . __('constants.delete') . '</a>';
		print '</div>';
	}
	print Form::file('picture_1',array('style'=>'width:280px;'));
	print '</div>';

	print '<div class="left">';

	if(isset($images['picture_2'])) {
		print '<div class="clearfix">';
		print '<img class="left" src="' . Uri::create(str_replace('original/','',$images['picture_2'])) . '" />';
		print '<a class="pic_delete left" href="' . Uri::create('admin/news/picture/delete/' . $id . '/picture_2') . '">' . __('constants.delete') . '</a>';
		print '</div>';
	}
	print Form::file('picture_2',array('style'=>'width:280px;'));
	print '</div>';


	print '<div class="left">';

	if(isset($images['picture_3'])) {
		print '<div class="clearfix">';
		print '<img class="left" src="' . Uri::create(str_replace('original/','',$images['picture_3'])) . '" />';
		print '<a class="pic_delete left" href="' . Uri::create('admin/news/picture/delete/' . $id . '/picture_3') . '">' . __('constants.delete') . '</a>';
		print '</div>';
	}
	print Form::file('picture_3',array('style'=>'width:280px;'));
	print '</div>';
?>
</div>
<?php
	print '<div class="actions">';

	print Form::submit('submit',__('news.edit.upload'),array('class'=>'btn primary'));

	print '</div>';
?>

<div id="editor">
	<?php print $text; ?>
</div>

<?php
	print '<div class="actions">';

	print Form::submit('submit',__('news.edit.submit'),array('class'=>'btn primary')) . ' ';
	print Form::submit('back',__('news.edit.back'),array('class'=>'btn'));

	print '</div>';

	print Form::close();
?>