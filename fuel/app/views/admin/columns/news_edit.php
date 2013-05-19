<script type="text/javascript" src="<?php print Uri::create('assets/js/tiny_mce/tiny_mce.js'); ?>"></script>
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
		print '<div class="single_picture clearfix">';
		print '<img src="' . Uri::create(str_replace('original/','thumb/',$images['picture_1'])) . '" />';
		print '<a data-nr="1" data-id="' . $id . '" class="gallery_pic_delete" href="' . Uri::create('admin/news/picture/delete/' . $id . '/picture_1') . '">' . __('constants.delete') . '</a>';
		print '</div>';
	}
	print Form::file('picture_1',array('style'=>'width:280px;'));
	print '</div>';

	print '<div class="left">';

	if(isset($images['picture_2'])) {
		print '<div class="single_picture clearfix">';
		print '<img src="' . Uri::create(str_replace('original/','thumb/',$images['picture_2'])) . '" />';
		print '<a data-nr="2" data-id="' . $id . '" class="gallery_pic_delete" href="' . Uri::create('admin/news/picture/delete/' . $id . '/picture_2') . '">' . __('constants.delete') . '</a>';
		print '</div>';
	}
	print Form::file('picture_2',array('style'=>'width:280px;'));
	print '</div>';


	print '<div class="left">';

	if(isset($images['picture_3'])) {
		print '<div class="single_picture clearfix">';
		print '<img src="' . Uri::create(str_replace('original/','thumb/',$images['picture_3'])) . '" />';
		print '<a data-nr="3" data-id="' . $id . '" class="gallery_pic_delete" href="' . Uri::create('admin/news/picture/delete/' . $id . '/picture_3') . '">' . __('constants.delete') . '</a>';
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
<div class="row" style="margin-bottom:20px;">
  <div class="picturemanager-button"><?php print Lang::get('picturemanager_button') ?></div>
</div>
<div class="row">
<?php print Form::textarea('editor',$text,array('style'=>'width:100%;height:400px;')); ?>
</div>
<div>
	<h3><?php print __('news.attachment.header'); ?></h3>
	<div class="clearfix">
		<?php print Form::label(__('news.attachment.site')); ?>
		<div class="input">
			<?php print Form::select('attachment',$attachment,array(0=>__('constants.not_set')) + model_db_site::asSelectBox(Session::get('lang_prefix'))); ?>
		</div>
	</div>
</div><!-- / -->

<?php
	print '<div class="actions">';

	print Form::submit('submit',__('news.edit.submit'),array('class'=>'btn primary')) . ' ';
	print Form::submit('back',__('news.edit.back'),array('class'=>'btn'));

	print '</div>';

	print Form::close();
?>
<script type="text/javascript">
	var dialog = new pcms.dialog('.gallery_pic_delete', {
		title : _prompt.header,
		text : _prompt.text,
		confirm : _prompt.ok,
		cancel : _prompt.cancel
	});
	dialog.onConfirm = function(helper, event) {
		var picture = 'picture_' + $(event.initiator).attr('data-nr');
		var id = $(event.initiator).attr('data-id');
		helper.post_data(_url + 'admin/news/picture/delete/' + id + '/' + picture, {});
	}
	dialog.render(); 

	var picturemanager = new pcms.picturemanager();
	picturemanager.build_button('.picturemanager-button');

	tinyMCE.init({
	  theme : "advanced",
	  mode : "textareas",
	  theme_advanced_toolbar_location : "top",
	  theme_advanced_buttons1 : "bold,italic,underline,strikethrough,forecolor,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,outdent,indent,blockquote,separator,undo,redo,image,bullist,numlist,table,link,code",
	  plugins : 'emotions,safari,inlinepopups',
	  theme_advanced_buttons1_add : "emotions",
	  language : '<?php print Session::get('lang_prefix') ?>'
	});
</script>