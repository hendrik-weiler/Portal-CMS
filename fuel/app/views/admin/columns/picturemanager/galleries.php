<?php if(empty($galleries)): ?>
<h3><?php print __('picturemanager.no_galleries'); ?></h3>
<?php endif; ?>
<?php foreach($galleries as $gallery): ?>

<div class="gallery">

	<h3><?php print $gallery->label ?></h3>
	<?php $path = 'uploads/' . Session::get('lang_prefix') . '/gallery/' . $gallery->id . '/thumbs/'; ?>
	<ul class="picture_list">
		<?php 
			$pictures = json_decode($gallery->parameter,true);
			$pictures = empty($pictures) ? array() : $pictures;
		?>
		<?php if(empty($pictures)): ?>
		<p><?php print __('picturemanager.no_pictures'); ?></p>
		<?php endif; ?>

		<?php foreach($pictures as $picture): ?>
		<li>
			<img src="<?php print Uri::create($path . $picture) ?>">
			<a class="get_link" href=""><?php print Lang::get('picturemanager.get_link') ?></a>
		</li>
		<?php endforeach; ?>
	</ul>

</div>
<?php endforeach; ?>

<script type="text/javascript">

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
		var index = $('.picture_list li a').index(event.currentTarget);

		$(text).attr('value',$('.picture_list li').eq(index).find('img').attr('src').replace('thumbs','big'));
		helper.set_new_content({
			text : text
		});
	}
	link_dialog.render(); 

</script>