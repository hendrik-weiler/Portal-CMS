<div class="file_uploader" style="overflow:hidden">
	<?php print Form::open(array('action'=>'admin/picturemanager/own_pictures/add','enctype'=>'multipart/form-data')); ?>
	<div class="span5" style="padding-top:5px;">
		<?php print Form::file('uploader');	?>
	</div>
	<div class="span4" style="padding-bottom:10px;">
		<?php print Form::submit('upload',Lang::get('picturemanager.upload_button'),array('class'=>'button')) ?>
	</div>
	<?php print Form::close(); ?>
</div>
<?php if(empty($pictures)): ?>
<h3><?php print __('picturemanager.no_pictures'); ?></h3>
<?php endif; ?>
<ul class="picture_list">
	<?php foreach($pictures as $picture): ?>
	<li>
		<img src="<?php print $picture ?>">
		<a class="get_link" href=""><?php print Lang::get('picturemanager.get_link') ?></a>
		<a class="delete_picture" data-src="<?php print $picture ?>" href=""><?php print Lang::get('picturemanager.delete_button') ?></a>
	</li>
	<?php endforeach; ?>
</ul>

<script type="text/javascript">
	
	// ------- delete dialog

	var dialog = new pcms.dialog('.delete_picture', {
		title : _prompt.header,
		text : _prompt.text,
		confirm : _prompt.ok,
		cancel : _prompt.cancel
	});
	dialog.onConfirm = function(helper, event) {
		helper.post_data(_url + 'admin/content/picturemanager/own_pictures/delete_image', {
			file : $(event.initiator).attr('data-src')
		});
	}
	dialog.render(); 

	// ------- link dialog

	var link_dialog = new pcms.dialog('.get_link', {
		title : "<?php print Lang::get('picturemanager.prompt.link_header') ?>",
		text : '',
		confirm : "<?php print Lang::get('picturemanager.prompt.close') ?>",
		cancel : '',
		no_cancel : true
	});
	link_dialog.onConfirm = function(helper, event) {
		helper.cancel_dialog();
	}
	link_dialog.onInitiate = function(helper, event) {
		var text = $('<input style="width:240px" value="" />');
		var index = $('.picture_list li a.get_link').index(event.currentTarget);

		console.log( $('.picture_list li').eq(index).find('img').attr('src'), index );
		$(text).attr('value',$('.picture_list li').eq(index).find('img').attr('src').replace('thumbs','original'));
		helper.set_new_content({
			text : text
		});
	}
	link_dialog.render(); 

</script>